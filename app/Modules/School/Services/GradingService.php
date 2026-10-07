<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\Registration;
use App\Modules\School\Models\TeachingUnit;
use App\Modules\School\Models\Subject;
use App\Modules\School\Models\Evaluation;
use App\Modules\School\Models\SubjectClassAverage;
use App\Modules\School\Models\StudentSubjectAverage;
use Illuminate\Support\Facades\DB;

class GradingService
{
    /**
     * Calcul de la moyenne d'un étudiant pour un ECUE / Matière sur une période (semestre/trimestre).
     */
    public static function calculateSubjectAverage(Registration $registration, int $subjectId, int$periodId): ?float
    {
        $evaluations = Evaluation::where(function ($q) use ($subjectId) {
                $q->where('school_subject_id',$subjectId)
                  ->orWhere('subject_id', $subjectId);
            })
            ->where(function ($q) use ($periodId) {
                $q->where('academic_period_id',$periodId)
                  ->orWhere('term_id', $periodId);
            })
            ->where('school_class_id', $registration->school_class_id)
            ->published()
            ->with(['grades' => function ($query) use ($registration) {
                $query->where('registration_id',$registration->id);
            }])
            ->get();

        if ($evaluations->isEmpty()) {
            return null;
        }

        $totalPoints = 0;
        $totalCoefficients = 0;
        $hasValidGrade = false;

        foreach ($evaluations as$evaluation) {
            $grade =$evaluation->grades->first();
            
            if ($grade) {
                if ($grade->is_absent) {
                    // Si l'absence n'est pas justifiée, la note vaut 0
                    if (!$grade->is_justified) {
                        $totalCoefficients +=$evaluation->coefficient;
                        $hasValidGrade = true;
                    }
                } elseif (!is_null($grade->score)) {
                    $maxScore =$evaluation->max_score ?? 20.00;
                    $normalizedScore =$maxScore > 0 ? ($grade->score / $maxScore) * 20 : 0;
                    
                    $totalPoints += $normalizedScore * $evaluation->coefficient;
                    $totalCoefficients +=$evaluation->coefficient;
                    $hasValidGrade = true;
                }
            }
        }

        return ($hasValidGrade &&$totalCoefficients > 0) 
            ? round($totalPoints / $totalCoefficients, 2) 
            : null;
    }

    /**
     * Calcul du PV d'une Unité d'Enseignement (UE) en mode LMD.
     */
    public static function calculateTeachingUnitDetails(Registration $registration, TeachingUnit $unit, int$periodId): array
    {
        $subjects =$unit->subjects;
        $totalPoints = 0;
        $totalCoefficients = 0;
        $subjectResults = [];

        foreach ($subjects as $subject) {$avg = self::calculateSubjectAverage($registration,$subject->id, $periodId);$subjectResults[] = [
                'subject' => $subject,
                'average' => $avg,
            ];

            if (!is_null($avg)) {$totalPoints += $avg * ($subject->coefficient ?? 1);
                $totalCoefficients += ($subject->coefficient ?? 1);
            }
        }

        $unitAverage =$totalCoefficients > 0 ? round($totalPoints / $totalCoefficients, 2) : null;
        
        // Règle LMD : UE Validée si moyenne >= 10/20
        $isValidated = !is_null($unitAverage) &&$unitAverage >= 10.00;
        $creditsEarned = $isValidated ? ($unit->credits ?? 0) : 0;

        return [
            'unit'           => $unit,
            'average'        => $unitAverage,
            'is_validated'   => $isValidated,
            'credits_earned' => $creditsEarned,
            'subjects'       => $subjectResults,
        ];
    }

    /**
     * Calcul des moyennes de classe, des rangs par étudiant et archivage complet pour un ECUE.
     */
    public static function calculateAndArchiveClassSubjectAverages(
        int $schoolId,
        int $classId,
        int $subjectId,
        int $periodId
    ): SubjectClassAverage {
        return DB::transaction(function () use ($schoolId,$classId, $subjectId,$periodId) {
            
            // 1. Récupérer tous les étudiants inscrits et confirmés
            $registrations = Registration::confirmed()
                ->where('school_class_id', $classId)
                ->get();

            $studentResults = [];

            // 2. Calculer la moyenne de chaque étudiant
            foreach ($registrations as $registration) {$avg = self::calculateSubjectAverage($registration,$subjectId, $periodId);$studentResults[] = [
                    'registration_id' => $registration->id,
                    'average'         => $avg,
                ];
            }

            // 3. Trier du plus grand au plus petit pour attribuer les rangs
            usort($studentResults, function ($a,$b) {
                if ($a['average'] === null) return 1;
                if ($b['average'] === null) return -1;
                return $b['average'] <=>$a['average'];
            });

            // 4. Calcul des rangs avec gestion des ex æquo
            $rank = 1;
            $previousAvg = null;
            $studentsWithAvg = array_filter($studentResults, fn($s) =>$s['average'] !== null);

            foreach ($studentResults as $index => &$result) {
                if ($result['average'] === null) {$result['rank'] = null;
                    $result['rank_formatted'] = 'N/A';
                    continue;
                }

                if ($previousAvg !== null && $result['average'] <$previousAvg) {
                    $rank =$index + 1;
                }

                // Vérifier s'il existe une égalité
                $isExAequo = false;
                if ($index > 0 && $studentResults[$index - 1]['average'] === $result['average']) {$isExAequo = true;
                }
                if ($index < count($studentResults) - 1 && $studentResults[$index + 1]['average'] === $result['average']) {$isExAequo = true;
                }

                $result['rank'] =$rank;
                $result['rank_formatted'] = self::formatRank($rank,$isExAequo);
                $previousAvg =$result['average'];
            }
            unset($result);

            // 5. Calcul des statistiques de classe
            $validAverages = array_column($studentsWithAvg, 'average');
            $classAverage = count($validAverages) > 0 ? round(array_sum($validAverages) / count($validAverages), 2) : 0;
            $maxAverage = count($validAverages) > 0 ? max($validAverages) : 0;
            $minAverage = count($validAverages) > 0 ? min($validAverages) : 0;
            $passedCount = count(array_filter($validAverages, fn($avg) =>$avg >= 10.0));

            // 6. Archivage global de la classe (`subject_class_averages`)
            $classAvgRecord = SubjectClassAverage::updateOrCreate(
                [
                    'school_class_id'    => $classId,
                    'academic_period_id' => $periodId,
                    'school_subject_id'  => $subjectId,
                ],
                [
                    'school_id'       => $schoolId,
                    'class_average'   => $classAverage,
                    'max_average'     => $maxAverage,
                    'min_average'     => $minAverage,
                    'total_students'  => count($studentResults),
                    'passed_count'    => $passedCount,
                    'calculated_at'   => now(),
                ]
            );

            // 7. Archivage individuel par étudiant (`student_subject_averages`)
            foreach ($studentResults as$res) {
                StudentSubjectAverage::updateOrCreate(
                    [
                        'subject_class_average_id' => $classAvgRecord->id,
                        'registration_id'          => $res['registration_id'],
                    ],
                    [
                        'average'        => $res['average'],
                        'rank'           => $res['rank'],
                        'rank_formatted' => $res['rank_formatted'],
                    ]
                );
            }

            return $classAvgRecord->load('studentAverages.registration.student');
        });
    }

    /**
     * Formate l'affichage du rang (ex: 1er, 2ème, 2ème ex).
     */
    private static function formatRank(int $rank, bool$isExAequo = false): string
    {
        $suffix = ($rank === 1) ? 'er' : 'ème';
        $formatted = $rank .$suffix;

        if ($isExAequo) {$formatted .= ' ex';
        }

        return $formatted;
    }
}
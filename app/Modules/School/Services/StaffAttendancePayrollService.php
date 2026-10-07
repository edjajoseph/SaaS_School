<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\Staff;
use App\Modules\School\Models\Payroll;
use App\Modules\School\Models\PayrollItem;
use App\Modules\School\Models\PayComponent;
use App\Modules\School\Models\HourlyRate;
use App\Modules\School\Models\TeacherSubjectRate;
use App\Modules\School\Models\TeacherAttendance;
use App\Modules\School\Models\StaffAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StaffAttendancePayrollService
{
    /**
     * Génère ou réactualise le bulletin de paie complet d'un membre du personnel pour une période donnée.
     */
    public function generateMonthlyPayroll(Staff $staff, string $startDate, string $endDate, int $userId): Payroll
    {
        return DB::transaction(function () use ($staff, $startDate, $endDate, $userId) {
            $contract = $staff->contracts()->where('status', 'active')->first();
            $isHourly = $contract && $contract->pay_type === 'hourly';

            $totalHours = 0;
            $grossAmount = 0;

            if ($isHourly) {
                // 1. Calcul pour le personnel à l'heure / Vacataires
                $attendances = TeacherAttendance::with(['schoolClass.level', 'schoolClass.series', 'schoolSubject'])
                    ->where('staff_id', $staff->id)
                    ->where('is_validated', true)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get();

                foreach ($attendances as $att) {
                    $rate = $this->resolveHourlyRate(
                        $staff,
                        $att->school_class_id,
                        $att->school_subject_id,
                        $att->schoolClass->level_id ?? null,
                        $att->schoolClass->series_id ?? null,
                        $att->session_type ?? 'CM'
                    );

                    $hours = $att->hours_done ?? 0;
                    $totalHours += $hours;
                    $grossAmount += ($hours * $rate);
                }
            } else {
                // 2. Calcul pour le personnel administratif / Salaire fixe (PAT)
                $grossAmount = $contract->base_salary_or_rate ?? $staff->base_salary ?? 0;
            }

            // Calcul des pénalités automatiques pour retard (> 15 minutes) sur la période
            $latenessPenalties = StaffAttendance::where('staff_id', $staff->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('penalty_amount');

            // Enregistrement / Mises à jour du bulletin de paie englobant
            $periodCode = Carbon::parse($startDate)->format('Ym');
            $payrollNumber = "PAY-{$periodCode}-" . str_pad($staff->id, 4, '0', STR_PAD_LEFT);

            $payroll = Payroll::updateOrCreate(
                [
                    'staff_id'     => $staff->id,
                    'period_start' => $startDate,
                    'period_end'   => $endDate,
                ],
                [
                    'school_id'         => $staff->school_id ?? 1,
                    'staff_contract_id' => $contract->id ?? null,
                    'payroll_number'    => $payrollNumber,
                    'base_salary'       => $isHourly ? 0 : $grossAmount,
                    'total_hours'       => $totalHours,
                    'gross_amount'      => $grossAmount,
                    'penalties_amount'  => $latenessPenalties,
                    'status'            => 'draft',
                    'processed_by'      => $userId,
                ]
            );

            // Reconstitution des lignes détaillées du bulletin
            $payroll->items()->delete();

            // Saisie de la déduction pour retard si applicable
            if ($latenessPenalties > 0) {
                PayrollItem::create([
                    'payroll_id'    => $payroll->id,
                    'label'         => 'Retenue pour retards/absences',
                    'type'          => 'deduction',
                    'base_amount'   => $grossAmount,
                    'rate_or_value' => 0,
                    'amount'        => $latenessPenalties,
                ]);
            }

            // Application des Impôts, Cotisations (ITS, CNPS, Mutuelle) et Primes
            $targetGroup = $isHourly ? 'vacant' : 'permanent';
            $components = PayComponent::where('is_active', true)
                ->where('school_id', $staff->school_id)
                ->whereIn('applies_to', ['all', $targetGroup])
                ->get();

            $totalGains = 0;
            $totalDeductions = $latenessPenalties;

            foreach ($components as $comp) {
                $itemAmount = ($comp->calculation_method === 'percentage')
                    ? round(($grossAmount * $comp->value) / 100, 2)
                    : $comp->value;

                PayrollItem::create([
                    'payroll_id'       => $payroll->id,
                    'pay_component_id' => $comp->id,
                    'label'            => $comp->name,
                    'type'             => $comp->type,
                    'base_amount'      => $grossAmount,
                    'rate_or_value'    => $comp->value,
                    'amount'           => $itemAmount,
                ]);

                if ($comp->type === 'gain') {
                    $totalGains += $itemAmount;
                } else {
                    $totalDeductions += $itemAmount;
                }
            }

            $netAmount = max(0, ($grossAmount + $totalGains) - $totalDeductions);

            $payroll->update([
                'bonuses_amount'   => $totalGains,
                'penalties_amount' => $totalDeductions,
                'net_amount'       => $netAmount,
            ]);

            return $payroll;
        });
    }

    /**
     * Résout le taux horaire selon la hiérarchie :
     * 1. Tarif négocié/customisé sur la matière et la classe (teacher_subject_rates)
     * 2. Grille de référence par Diplôme x Niveau x Filière (hourly_rates)
     * 3. Grille globale par Diplôme uniquement (hourly_rates)
     */
    public function resolveHourlyRate(
        Staff $staff,
        ?int $classId,
        ?int $subjectId,
        ?int $levelId,
        ?int $seriesId,
        string $sessionType
    ): float {
        $schoolId = $staff->school_id;
        $degreeId = $staff->degree_id;

        // Priorité 1 : Taux appliqué/négocié spécifiquement pour le cours et la classe
        if ($classId && $subjectId) {
            $customRate = TeacherSubjectRate::where('school_id', $schoolId)
                ->where('staff_id', $staff->id)
                ->where('school_class_id', $classId)
                ->where('school_subject_id', $subjectId)
                ->first();

            if ($customRate) {
                return $this->extractRateByType($customRate, $sessionType);
            }
        }

        // Priorité 2 : Recherche dans la grille par Diplôme + Niveau + Filière
        $gridRate = null;
        if ($degreeId && $levelId) {
            $gridRate = HourlyRate::where('school_id', $schoolId)
                ->where('degree_id', $degreeId)
                ->where('level_id', $levelId)
                ->where(function ($q) use ($seriesId) {
                    $q->where('series_id', $seriesId)->orWhereNull('series_id');
                })
                ->first();
        }

        // Priorité 3 : Recherche fallback dans la grille par Diplôme uniquement
        if (!$gridRate && $degreeId) {
            $gridRate = HourlyRate::where('school_id', $schoolId)
                ->where('degree_id', $degreeId)
                ->whereNull('level_id')
                ->first();
        }

        if ($gridRate) {
            return $this->extractRateByType($gridRate, $sessionType);
        }

        return 0.00;
    }

    /**
     * Extrait la valeur numérique du tarif selon la nature de la séance (CM, TD, TP, EXAMEN)
     */
    private function extractRateByType(object $rateSource, string $sessionType): float
    {
        return match (strtoupper($sessionType)) {
            'CM'     => (float) $rateSource->rate_cm,
            'TD'     => (float) $rateSource->rate_td,
            'TP'     => (float) $rateSource->rate_tp,
            'EXAMEN' => (float) ($rateSource->rate_examen ?? $rateSource->rate_td),
            default  => (float) $rateSource->rate_cm,
        };
    }

    /**
     * Génère un récapitulatif global de la période pour affichage tableau de bord
     */
    public function getPayrollSummary(string $startDate, string $endDate, ?int $staffId = null): array
    {
        $query = Payroll::with(['staff.personne', 'staff.contracts'])
            ->whereBetween('period_start', [$startDate, $endDate]);

        if ($staffId) {
            $query->where('staff_id', $staffId);
        }

        $payrolls = $query->get();

        $summary = [];
        foreach ($payrolls as $pay) {
            $summary[$pay->staff_id] = [
                'staff_name'      => $pay->staff->personne->nom_complet ?? 'N/A',
                'total_hours'     => $pay->total_hours,
                'gross_total'     => $pay->gross_amount,
                'bonuses_total'   => $pay->bonuses_amount,
                'penalties_total' => $pay->penalties_amount,
                'net_total'       => $pay->net_amount,
                'status'          => $pay->status,
            ];
        }

        return $summary;
    }
}
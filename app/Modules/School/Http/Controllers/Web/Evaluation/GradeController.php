<?php

namespace App\Modules\School\Http\Controllers\Web\Evaluation;

use App\Http\Controllers\Controller;

use App\Modules\School\Models\Evaluation;
use App\Modules\School\Models\Grade;
use App\Modules\School\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    /**
     * Charge la grille de saisie rapide pour une évaluation donnée.
     */
    public function grid(Evaluation $evaluation)
    {
        // TEST D'INTERCEPTION IMMÉDIAT
        
        $evaluation->load(['schoolClass', 'subject', 'type']);

        // Récupérer tous les étudiants inscrits et confirmés dans cette classe
        $registrations = Registration::with('student.personne')
            ->where('school_class_id', $evaluation->school_class_id)
            ->where('status', 'confirmed')
            ->get();

        // Récupérer les notes déjà saisies (indexées par registration_id)
        $existingGrades = Grade::where('evaluation_id', $evaluation->id)
            ->get()
            ->keyBy('registration_id');

        return view('School::evaluation.grades.grid', compact('evaluation', 'registrations', 'existingGrades'));
    }

    /**
     * Enregistrement en lot des notes de la grille.
     */
    public function batchStore(Request $request, Evaluation $evaluation)
    {
        
        // Validation : 'score' doit être nullable car les champs disabled ne sont pas transmis dans $request
        $request->validate([
            'grades' => 'required|array',
            'grades.*.registration_id' => 'required|exists:registrations,id',
            'grades.*.score' => 'nullable|numeric|min:0|max:' . $evaluation->max_score,
            'grades.*.is_absent' => 'nullable|boolean',
            'grades.*.is_justified' => 'nullable|boolean',
            'grades.*.remarks' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            if ($request->has('grades')) {
                foreach ($request->grades as $item) {
                    $isAbsent = isset($item['is_absent']) && $item['is_absent'] == '1';
                    
                    // Si l'étudiant est absent ou si le score n'a pas été renseigné, $score est null
                    $score = $isAbsent ? null : (isset($item['score']) && $item['score'] !== '' ? $item['score'] : null);

                    Grade::updateOrCreate(
                        [
                            'evaluation_id' => $evaluation->id,
                            'registration_id' => $item['registration_id'],
                        ],
                        [
                            'score' => $score,
                            'is_absent' => $isAbsent,
                            'is_justified' => isset($item['is_justified']) && $item['is_justified'] == '1',
                            'remarks' => $item['remarks'] ?? null,
                        ]
                    );
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => __('Notes enregistrées avec succès.')]);
            }

            return redirect()
                ->route('evaluation.evaluations.grades.grid', $evaluation->id)
                ->with('success', __('Les notes ont été enregistrées avec succès.'));

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            // Redirection explicite vers la grille pour éviter de chuter sur route /evaluation/grades
            return redirect()
                ->route('evaluation.evaluations.grades.grid', $evaluation->id)
                ->with('error', __('Erreur lors de l\'enregistrement : ') . $e->getMessage())
                ->withInput();
        }
    }
}
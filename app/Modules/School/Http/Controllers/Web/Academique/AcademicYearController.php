<?php

namespace App\Modules\School\Http\Controllers\Web\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\AcademicYear;
use App\Modules\School\Http\Requests\AcademicYearRequest;
use App\Modules\School\Models\School; // Ajustez l'espace de noms selon votre architecture
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    /**
     * Affiche la liste des années académiques.
     */
    public function index()
    {
        $academicYears = AcademicYear::withCount(['registrations', 'terms'])
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('School::academique.academic_years.index', compact('academicYears'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('School::academique.academic_years.create');
    }

    /**
     * Enregistre une nouvelle année académique.
     */
    public function store(AcademicYearRequest $request)
    {
        $academicYear = DB::transaction(function () use ($request) {
            $isCurrent = $request->boolean('is_current');

            // Si cette nouvelle année est définie comme courante, réinitialiser les autres
            if ($isCurrent) {
                AcademicYear::query()->update(['is_current' => false]);
            }

            $newYear = AcademicYear::create([
                'name'       => $request->name,
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'is_current' => $isCurrent,
            ]);

            // Mettre à jour la table schools pour pointer vers cette nouvelle année
            if ($isCurrent) {
                School::query()->update(['current_academic_year_id' => $newYear->id]);
            }

            return $newYear;
        });

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Année académique créée avec succès.',
                'data'    => $academicYear
            ], 201);
        }

        return redirect()
            ->route('organisation.academic-years.index')
            ->with('success', 'Année académique créée avec succès.');
    }

    /**
     * Affiche les détails d'une année académique.
     */
    public function show(AcademicYear $academicYear)
    {
        $academicYear->load(['terms', 'currentTerm']);
        
        return view('School::academique.academic_years.show', compact('academicYear'));
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(AcademicYear $academicYear)
    {
        return view('School::academique.academic_years.edit', compact('academicYear'));
    }

    /**
     * Met à jour une année académique existante.
     */
    public function update(AcademicYearRequest $request, AcademicYear $academicYear)
    {
        DB::transaction(function () use ($request, $academicYear) {
            $isMakingCurrent = $request->boolean('is_current');

            if ($isMakingCurrent && !$academicYear->is_current) {
                AcademicYear::where('id', '!=', $academicYear->id)->update(['is_current' => false]);
            }

            $academicYear->update([
                'name'       => $request->name,
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'is_current' => $isMakingCurrent,
            ]);

            // Mettre à jour la table schools si l'année est (ou devient) courante
            if ($isMakingCurrent) {
                School::query()->update(['current_academic_year_id' => $academicYear->id]);
            }
        });

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Année académique mise à jour avec succès.',
                'data'    => $academicYear
            ]);
        }

        return redirect()
            ->route('organisation.academic-years.index')
            ->with('success', 'Année académique mise à jour avec succès.');
    }

    /**
     * Supprime une année académique.
     */
    public function destroy(Request $request, AcademicYear $academicYear)
    {
        if ($academicYear->students()->exists()) {
            $msg = 'Impossible de supprimer une année académique associée à des élèves.';
            
            return $request->wantsJson()
                ? response()->json(['error' => $msg], 422)
                : back()->with('error', $msg);
        }

        $academicYear->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Année académique supprimée avec succès.']);
        }

        return redirect()
            ->route('organisation.academic-years.index')
            ->with('success', 'Année académique supprimée avec succès.');
    }

    /**
     * Bascule rapidement une année académique en année active (Action d'arrière-plan).
     */
    public function toggleCurrent(AcademicYear $academicYear)
    {
        DB::transaction(function () use ($academicYear) {
            AcademicYear::query()->update(['is_current' => false]);
            $academicYear->update(['is_current' => true]);
        });

        return back()->with('success', "L'année {$academicYear->name} est désormais l'année active.");
    }
}
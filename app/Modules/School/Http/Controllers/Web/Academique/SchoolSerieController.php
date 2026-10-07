<?php

namespace App\Modules\School\Http\Controllers\Web\Academique;

use App\Http\Controllers\Controller;

use App\Modules\School\Models\SchoolSerie;
use App\Modules\School\Models\School;
use App\Modules\School\Models\Serie;
use Illuminate\Http\Request;

class SchoolSerieController extends Controller
{
    /**
     * Liste des associations écoles - séries.
     */
    public function index()
    {
        $schoolSeries = SchoolSerie::with(['school', 'serie'])->latest()->paginate(15);
        return view('School::academique.school_series.index', compact('schoolSeries'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $schools = School::all();
        $series = Serie::all();
        return view('School::academique.school_series.create', compact('schools', 'series'));
    }

    /**
     * Enregistrement en BDD.
     */
    public function store(Request $request)
    {
        $request->validate([
            'school_id'  => 'required|exists:schools,id',
            'serie_ids'  => 'required|array|min:1',
            'serie_ids.*'=> 'exists:series,id',
        ]);

        $schoolId = $request->input('school_id');
        $serieIds = $request->input('serie_ids');
        $isActive = $request->has('is_active');

        foreach ($serieIds as $serieId) {
            SchoolSerie::updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'serie_id'  => $serieId,
                ],
                [
                    'is_active' => $isActive,
                ]
            );
        }

        return redirect()->route('academic.school-series.index')
            ->with('success', count($serieIds) . ' série(s) associée(s) à l\'établissement avec succès.');
    }
    /**
     * Affichage d'un élément.
     */
    public function show(SchoolSerie $school_series)
    {
        $school_series->load(['school', 'serie']);
        return view('School::academique.school_series.show', compact('school_series'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(SchoolSerie $school_series)
    {
        $schools = School::all();
        $series = Serie::all();
        return view('School::academique.school_series.edit', compact('school_series', 'schools', 'series'));
    }

    /**
     * Mise à jour en BDD.
     */
    public function update(Request $request, SchoolSerie $school_series)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'serie_id'  => 'required|exists:series,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $school_series->update($validated);

        return redirect()->route('academic.school-series.index')
            ->with('success', 'Association mise à jour avec succès.');
    }

    /**
     * Suppression en BDD.
     */
    public function destroy(SchoolSerie $school_series)
    {
        $school_series->delete();

        return redirect() ->route('academic.school-series.index')
            ->with('success', 'Association supprimée avec succès.');
    }
}
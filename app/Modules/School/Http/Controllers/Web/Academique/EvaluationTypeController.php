<?php

namespace App\Modules\School\Http\Controllers\Web\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\School;
use App\Modules\School\Models\EvaluationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationTypeController extends Controller
{
    public function index()
    {
        $types = EvaluationType::all();

        return view('School::academique.evaluation_types.index', compact('types'));
    }

    public function create()
    {       
        $schools = School::active()->orderBy('name')->get();
        
        return view('School::academique.evaluation_types.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'           => 'required|string|max:20|unique:evaluation_types,code',
            'name'           => 'required|string|max:100',
            'default_weight' => 'required|numeric|min:0',
            'is_catch_up'     => 'nullable|boolean',
            'school_id'      => 'nullable|exists:schools,id',
        ]);

        // 1. Détermination du school_id
        $schoolId = $request->input('school_id') 
                    ?? Auth::user()->school_id 
                    ?? School::first()?->id;

        // 2. Bloquer si aucune école n'est disponible dans la base tenant
        if (!$schoolId) {
            return redirect()->back()->withErrors([
                'school_id' => __('Impossible de déterminer l\'école associée. Veuillez vérifier les données de l\'établissement.')
            ]);
        }

        // 3. Réaffectation explicite dans le tableau d'insertion
        $validated['school_id']   = $schoolId;
        $validated['is_catch_up'] = $request->has('is_catch_up');

        EvaluationType::create($validated);

        return redirect()->back()->with('success', __('Type d\'évaluation ajouté avec succès.'));
    }

    public function edit(EvaluationType $evaluationType)
    {
        $schools = School::active()->orderBy('name')->get();

        return view('School::academique.evaluation_types.edit', compact('evaluationType', 'schools'));
    }

    public function update(Request $request, EvaluationType $evaluationType)
    {
        $validated = $request->validate([
            'code'           => 'required|string|max:20|unique:evaluation_types,code,' . $evaluationType->id,
            'name'           => 'required|string|max:100',
            'default_weight' => 'required|numeric|min:0',
            'is_catch_up'     => 'nullable|boolean',
            'school_id'      => 'nullable|exists:schools,id',
        ]);

        $validated['is_catch_up'] = $request->has('is_catch_up');
        $evaluationType->update($validated);

        return redirect()->back()->with('success', __('Type d\'évaluation mis à jour.'));
    }

    public function toggleStatus(EvaluationType $evaluationType)
    {
        $evaluationType->update(['is_active' => !$evaluationType->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $evaluationType->is_active,
            'message'   => __('Statut mis à jour.')
        ]);
    }

    public function destroy(EvaluationType $evaluationType)
    {
        $evaluationType->delete();
        return redirect()->back()->with('success', __('Type d\'évaluation supprimé.'));
    }
}
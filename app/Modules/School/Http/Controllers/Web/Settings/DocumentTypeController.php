<?php

namespace App\Modules\School\Http\Controllers\Web\Settings;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentTypeController extends Controller
{
    /**
     * Liste des types de documents
     */
    public function index(Request $request)
    {
        $query = DocumentType::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $documentTypes = $query->orderBy('name', 'asc')->paginate(15)->withQueryString();

        return view('school::settings.document_types.index', compact('documentTypes'));
    }

    /**
     * Formulaire de création (chargé généralement via Modal AJAX)
     */
    public function create()
    {
        return view('school::settings.document_types.create');
    }

    /**
     * Enregistrer un nouveau type de document
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:document_types,name',
            'code'        => 'required|string|max:255|unique:document_types,code',
            'is_required' => 'nullable|boolean',
            'is_active'   => 'nullable|boolean',
        ]);

        try {
            DocumentType::create([
                'name'        => $validated['name'],
                'code'        => strtoupper($validated['code']),
                'is_required' => $request->has('is_required'),
                'is_active'   => $request->has('is_active'),
            ]);

            /*return redirect()->route('school.settings.document-types.index')
                ->with('success', 'Type de document créé avec succès.');*/
            return redirect()->back()->with('success', 'Type de document créé avec succès.');

        } catch (\Exception $e) {
            Log::error("Erreur création DocumentType : " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du type de document.');
        }
    }

    /**
     * Formulaire d'édition (chargé via Modal AJAX)
     */
    public function show($id)
    {
        $documentType = DocumentType::findOrFail($id);

        return view('school::settings.document_types.show', compact('documentType'));
    }

    /**
     * Formulaire d'édition (chargé via Modal AJAX)
     */
    public function edit($id)
    {
        $documentType = DocumentType::findOrFail($id);

        return view('school::settings.document_types.edit', compact('documentType'));
    }

    /**
     * Mettre à jour un type de document
     */
    public function update(Request $request, $id)
    {
        $documentType = DocumentType::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:document_types,name,' . $id,
            'code'        => 'required|string|max:255|unique:document_types,code,' . $id,
            'is_required' => 'nullable|boolean',
            'is_active'   => 'nullable|boolean',
        ]);

        try {
            $documentType->update([
                'name'        => $validated['name'],
                'code'        => strtoupper($validated['code']),
                'is_required' => $request->boolean('is_required') ? 1 : 0,
                'is_active'   => $request->boolean('is_active') ? 1 : 0,
            ]);

            return redirect()->back()->with('success', 'Type de document mis à jour avec succès.');

        } catch (\Exception $e) {
            Log::error("Erreur modification DocumentType : " . $e->getMessage());
            
            // Affichage du message réel de l'exception pour le débogage si besoin
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Activer / Désactiver un type de document
     */
    public function toggleStatus($id)
    {
        $documentType = DocumentType::findOrFail($id);
        
        $documentType->update([
            'is_active' => !$documentType->is_active
        ]);

        $statusLabel = $documentType->is_active ? 'activé' : 'désactivé';

        return redirect()->back()->with('success', "Le type de document a été {$statusLabel} avec succès.");
    }

    /**
     * Supprimer un type de document (SoftDelete)
     */
    public function destroy($id)
    {
        $documentType = DocumentType::findOrFail($id);

        // Vérification de la présence de documents étudiants déjà associés
        if ($documentType->studentDocuments()->exists()) {
            return redirect()->back()->with('error', 'Impossible de supprimer ce type de document car il est déjà associé à des dossiers d\'étudiants.');
        }

        try {
            $documentType->delete();

            return redirect()->route('school.settings.document-types.index')
                ->with('success', 'Type de document supprimé avec succès.');

        } catch (\Exception $e) {
            Log::error("Erreur suppression DocumentType : " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la suppression.');
        }
    }
}
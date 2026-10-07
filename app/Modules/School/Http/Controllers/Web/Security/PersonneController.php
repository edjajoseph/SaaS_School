<?php

namespace App\Modules\School\Http\Controllers\Web\Security;

use App\Http\Controllers\Controller;
use App\Models\Personne;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonneController extends Controller
{
    /**
     * Liste des personnes (avec filtres de recherche et pagination).
     */
    public function index(Request $request)
    {
        $query = Personne::with('user');

        // Recherche optionnelle (nom, prénom, téléphone, email)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $personnes = $query->latest()->paginate(10)->withQueryString();

        return  view('School::security.personnes.index', compact('personnes'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return  view('School::security.personnes.create');
    }

    /**
     * Enregistrement d'une nouvelle personne.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'       => ['required', 'string', 'max:255'],
            'prenoms'   => ['required', 'string', 'max:255'],
            'sexe'      => ['required', Rule::in(['M', 'F'])], // Adaptable selon vos valeurs (ex: Homme/Femme)
            'telephone' => ['nullable', 'string', 'max:20'],
            'email'     => ['nullable', 'email', 'max:255', 'unique:personnes,email'],
        ]);

        $personne = Personne::create($validated);

        return redirect()->route('personnes.index')
            ->with('success', 'Fiche personne créée avec succès.');
    }

    /**
     * Affichage d'une personne avec son compte utilisateur lié.
     */
    public function show(Personne $personne)
    {
        $personne->load('user.roles');

        return  view('School::security.personnes.show', compact('personne'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Personne $personne)
    {
        return  view('School::security.personnes.edit', compact('personne'));
    }

    /**
     * Mise à jour des informations de la personne.
     */
    public function update(Request $request, Personne $personne)
    {
        $validated = $request->validate([
            'nom'       => ['required', 'string', 'max:255'],
            'prenoms'   => ['required', 'string', 'max:255'],
            'sexe'      => ['required', Rule::in(['M', 'F'])],
            'telephone' => ['nullable', 'string', 'max:20'],
            'email'     => ['nullable', 'email', 'max:255', Rule::unique('personnes', 'email')->ignore($personne->id)],
        ]);

        $personne->update($validated);

        return redirect()->route('personnes.index')
            ->with('success', 'Informations mises à jour avec succès.');
    }

    /**
     * Suppression d'une personne (avec gestion du compte lié).
     */
    public function destroy(Personne $personne)
    {
        // Empêche la suppression si un compte utilisateur y est rattaché
        if ($personne->user()->exists()) {
            return redirect()->route('personnes.index')
                ->with('error', 'Impossible de supprimer cette personne car un compte utilisateur y est associé.');
        }

        $personne->delete();

        return redirect()->route('personnes.index')
            ->with('success', 'Fiche personne supprimée avec succès.');
    }
}
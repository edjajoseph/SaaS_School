<?php

namespace App\Modules\School\Http\Controllers\Web\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Level;
use App\Modules\School\Models\School;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\Serie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    /**
     * Vérifie si l'utilisateur est un super administrateur.
     */
    private function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user->hasRole('admin-ecole') || $user->hasRole('super-admin') || $user->is_admin);
    }

    /**
     * Récupère l'ID de l'établissement courant pour un utilisateur non-admin.
     */
    private function getUserSchoolId(): ?int
    {
        return auth()->user()->school_id ?? null;
    }

    /**
     * Liste des classes avec filtres (adaptée au rôle Admin / Utilisateur).
     */
    public function index(Request $request): View
    {
        $isAdmin = $this->isAdmin();
        $userSchoolId = $this->getUserSchoolId();

        $query = SchoolClass::with(['school', 'level']);

        // Isolation des données : Si pas Admin, restreindre à son propre établissement
        if (!$isAdmin) {
            $query->where('school_id', $userSchoolId);
        } elseif ($request->filled('school_id')) {
            $query->where('school_id', $request->input('school_id'));
        }

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->input('level_id'));
        }

        if ($request->has('is_active') && $request->input('is_active') !== null) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $classes = $query->orderBy('name')->paginate(15)->withQueryString();
        
        // Un admin voit tous les établissements, un utilisateur ne voit que le sien
        $schools = $isAdmin ? School::active()->orderBy('name')->get() : collect();
        $levels = Level::all();

        return  view('School::academique.school_classes.index', compact('classes', 'schools', 'levels', 'isAdmin'));
    }

    /**
     * Formulaire de création (Rendu Ajax / Modale ou vue classique).
     */
    public function create(Request $request)
    {
        $isAdmin = $this->isAdmin();

        $schools = $isAdmin ? School::active()->orderBy('name')->get() : collect();

        // Utilisation de select('levels.*') pour éviter la collision d'ID avec la table pivot
        $levels = Level::join('level_school', 'level_school.level_id', '=', 'levels.id')
            ->select('levels.*')
            ->distinct()
            ->get();

        // Utilisation de select('series.*') pour éviter la collision d'ID avec la table pivot
        $series = Serie::join('school_series', 'school_series.serie_id', '=', 'series.id')
            ->select('series.*')
            ->distinct()
            ->get();

        $viewPath = view()->exists('School::academique.school_classes.modal.create') 
            ? 'School::academique.school_classes.modal.create' 
            : 'School::academique.school_classes.create';

        // Ajout obligatoire de 'series' dans le compact pour les appels AJAX
        if ($request->ajax()) {
            return view($viewPath, compact('schools', 'levels', 'series', 'isAdmin'));
        }

        return view($viewPath, compact('schools', 'levels', 'series', 'isAdmin'));
    }

    /**
     * Enregistre une nouvelle classe.
     */
    public function store(Request $request): RedirectResponse
    {
        $isAdmin = $this->isAdmin();
        
        // Si l'utilisateur n'est pas Admin, force son school_id
        if (!$isAdmin) {
            $request->merge(['school_id' => $this->getUserSchoolId()]);
        }

        $validated = $request->validate([
            'school_id'   => ['required', 'exists:schools,id'],
            'level_id'    => ['required', 'exists:levels,id'],
            'serie_id'    => ['required', 'exists:series,id'],
            'name'        => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('school_classes')->where(fn ($q) => $q->where('school_id', $request->school_id)),
            ],
            'code'        => ['nullable', 'string', 'max:50'],
            'capacity'    => ['nullable', 'integer', 'min:1'],
            'tuition_fee' => ['nullable', 'numeric', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['tuition_fee'] = $validated['tuition_fee'] ?? 0;

        $schoolClass = SchoolClass::create($validated);

        return redirect()->route('organisation.classes.index')
                         ->with('success', 'Classe créée avec succès.');
    }

    /**
     * Affiche les détails d'une classe.
     */
    public function show(Request $request, SchoolClass $class)
    {
        $this->authorizeAccess($class);

        $class->load(['school', 'level','serie', 'registrations.student']);

        $viewPath = view()->exists('School::academique.school_classes.modal.show') 
            ? 'School::academique.school_classes.modal.show' 
            : 'School::academique.school_classes.show';

        if ($request->ajax()) {
            return view($viewPath, compact('class'));
        }

        return view($viewPath, compact('class'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Request $request, SchoolClass $class)
    {
        // 1. Autorisation
        $this->authorizeAccess($class);

        $isAdmin = $this->isAdmin();
        $schools = $isAdmin ? School::active()->orderBy('name')->get() : collect();

        // 2. Sélection explicite pour éviter la collision d'ID avec les tables pivots
        $levels = Level::join('level_school', 'level_school.level_id', '=', 'levels.id')
            ->select('levels.*')
            ->distinct()
            ->get();

        $series = Serie::join('school_series', 'school_series.serie_id', '=', 'series.id')
            ->select('series.*')
            ->distinct()
            ->get();

        // 3. Détermination de la vue
        $viewPath = view()->exists('School::academique.school_classes.modal.edit') 
            ? 'School::academique.school_classes.modal.edit' 
            : 'School::academique.school_classes.edit';

        // 4. Passage de 'class' (au singulier) pour correspondre au Route Model Binding
        return view($viewPath, compact('class', 'schools', 'levels', 'series', 'isAdmin'));
    }

    /**
     * Met à jour une classe.
     */
    public function update(Request $request, SchoolClass $class): RedirectResponse|JsonResponse
    {
        $this->authorizeAccess($class);        
    
        $isAdmin = $this->isAdmin();
    
        // Récupérer un school_id garanti (depuis la requête ou conserver celui existant)
        $schoolId = $isAdmin 
            ? $request->input('school_id', $class->school_id) 
            : $class->school_id;
    
        // Réinjecter dans la requête pour la validation
        $request->merge(['school_id' => $schoolId]);
    
        $validated = $request->validate([
            'school_id'   => ['required', 'exists:schools,id'],
            'level_id'    => ['required', 'exists:levels,id'],
            'serie_id'    => ['nullable', 'exists:series,id'], // Rendu nullable si certaines classes n'ont pas de série
            'name'        => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('school_classes', 'name')
                    ->ignore($class->id)
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],
            'code'        => ['required', 'string', 'max:50'],
            'capacity'    => ['nullable', 'integer', 'min:1'],
            'tuition_fee' => ['required', 'numeric', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);
    
        // Formatage explicite du booléen pour les checkboxes HTML
        $validated['is_active'] = $request->boolean('is_active');
    
        $class->update($validated);
    
        // Réponse adaptée selon que la requête vienne d'une modal AJAX ou d'un formulaire classique
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Classe mise à jour avec succès.',
                'data'    => $class,
            ]);
        }
    
        return redirect()->route('organisation.classes.index')
                        ->with('success', 'Classe mise à jour avec succès.');
    }

    /**
     * Bascule l'état actif / inactif d'une classe.
     */
    public function toggleActive($class): RedirectResponse
    {
        // Résolution explicite si le Route Model Binding ne correspond pas au nom de variable
        $schoolClass = $class instanceof SchoolClass ? $class : SchoolClass::findOrFail($class);

        $this->authorizeAccess($schoolClass);

        // Modification directe de la propriété
        $schoolClass->is_active = !$schoolClass->is_active;
        $schoolClass->save(); // save() est plus direct et sûr que update()

        return redirect()->back()->with('success', 'Statut de la classe mis à jour.');
    }

    /**
     * Supprime une classe.
     */
    public function destroy(SchoolClass $class): RedirectResponse
    {
        $this->authorizeAccess($class);

        $class->delete();

        return redirect()->route('organisation.classes.index')
                         ->with('success', 'Classe supprimée avec succès.');
    }

    /**
     * Sécurité : Vérifie si un utilisateur non-admin tente d'accéder à la classe d'une autre école.
     */
    private function authorizeAccess(SchoolClass $class): void
    {
        if (!$this->isAdmin() && $class->school_id !== $this->getUserSchoolId()) {
            abort(403, 'Action non autorisée sur cet établissement.');
        }
    }
}
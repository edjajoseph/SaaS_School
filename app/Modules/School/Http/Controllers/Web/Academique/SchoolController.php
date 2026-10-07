<?php

namespace App\Modules\School\Http\Controllers\Web\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\AcademicYear;
use App\Modules\School\Models\Cycle;
use App\Modules\School\Models\Level;
use App\Modules\School\Models\School;
use App\Modules\School\Models\Serie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SchoolController extends Controller
{
    /**
     * Affiche la liste des établissements.
     */
    public function index(Request $request): View
    {
        $query = School::with('currentAcademicYear');

        if ($request->has('is_active') && $request->input('is_active') !== null) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $schools = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('School::academique.schools.index', compact('schools'));
    }

    /**
     * Affiche le formulaire de création d'un établissement.
     */
    public function create(): View
    {
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $cycles = Cycle::where('is_active', true)->orderBy('sequence_order')->get();
        $series = Serie::where('is_active', true)->orderBy('name')->get();
        $levels = Level::where('is_active', true)->orderBy('sequence_order')->get();

        return view('School::academique.schools.create', compact('academicYears', 'cycles', 'series', 'levels'));
    }

    /**
     * Enregistre un nouvel établissement depuis un formulaire Blade.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_academic_year_id' => ['nullable', 'exists:academic_years,id'],
            'name'                     => ['required', 'string', 'max:255'],
            'code'                     => ['required', 'string', 'max:50', 'unique:schools,code'],
            'official_approval_number' => ['nullable', 'string', 'max:255'],
            'logo'                     => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'stamp'                    => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'email'                    => ['nullable', 'email', 'max:255'],
            'phone_1'                  => ['nullable', 'string', 'max:30'],
            'phone_2'                  => ['nullable', 'string', 'max:30'],
            'po_box'                   => ['nullable', 'string', 'max:255'],
            'country'                  => ['nullable', 'string', 'size:2'],
            'city'                     => ['nullable', 'string', 'max:255'],
            'municipality'             => ['nullable', 'string', 'max:255'],
            'address'                  => ['nullable', 'string'],
            'status'                   => ['required', Rule::in(['private', 'public', 'confessional'])],
            'website'                  => ['nullable', 'url', 'max:255'],
            'document_header'          => ['nullable', 'string', 'max:255'],
            'document_footer'          => ['nullable', 'string'],
            'is_active'                => ['sometimes', 'boolean'],

            // Configuration des pivots (Listes sélectionnées)
            'cycles'                   => ['nullable', 'array'],
            'cycles.*'                 => ['exists:cycles,id'],
            'series'                   => ['nullable', 'array'],
            'series.*'                 => ['exists:series,id'],
            'levels'                   => ['nullable', 'array'],
            'levels.*'                 => ['exists:levels,id'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('schools/logos', 'public');
        }

        if ($request->hasFile('stamp')) {
            $validated['stamp_path'] = $request->file('stamp')->store('schools/stamps', 'public');
        }

        $school = DB::transaction(function () use ($validated, $request) {
            $school = School::create($validated);

            // Synchronisation des configurations
            $school->cycles()->sync($request->input('cycles', []));
            $school->series()->sync($request->input('series', []));
            $school->levels()->sync($request->input('levels', []));

            return $school;
        });

        return redirect()->route('organisation.schools.index', $school)
                         ->with('success', 'Établissement créé et configuré avec succès.');
    }

    /**
     * Affiche la fiche détaillée d'un établissement.
     */
    public function show(School $school): View
    {
        $school->load(['currentAcademicYear', 'academicYears', 'cycles', 'series', 'levels']);

        return view('School::academique.schools.show', compact('school'));
    }

    /**
     * Affiche le formulaire d'édition d'un établissement.
     */
    public function edit(School $school): View
    {
        $school->load(['cycles', 'series', 'levels']);

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $cycles = Cycle::where('is_active', true)->orderBy('sequence_order')->get();
        $series = Serie::where('is_active', true)->orderBy('name')->get();
        $levels = Level::where('is_active', true)->orderBy('sequence_order')->get();

        return view('School::academique.schools.edit', compact('school', 'academicYears', 'cycles', 'series', 'levels'));
    }

    /**
     * Met à jour un établissement depuis le formulaire Blade.
     */
    public function update(Request $request, School $school): RedirectResponse
    {
        $validated = $request->validate([
            'current_academic_year_id' => ['nullable', 'exists:academic_years,id'],
            'name'                     => ['required', 'string', 'max:255'],
            'code'                     => ['required', 'string', 'max:50', Rule::unique('schools', 'code')->ignore($school->id)],
            'official_approval_number' => ['nullable', 'string', 'max:255'],
            'logo'                     => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'stamp'                    => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'email'                    => ['nullable', 'email', 'max:255'],
            'phone_1'                  => ['nullable', 'string', 'max:30'],
            'phone_2'                  => ['nullable', 'string', 'max:30'],
            'po_box'                   => ['nullable', 'string', 'max:255'],
            'country'                  => ['nullable', 'string', 'size:2'],
            'city'                     => ['nullable', 'string', 'max:255'],
            'municipality'             => ['nullable', 'string', 'max:255'],
            'address'                  => ['nullable', 'string'],
            'status'                   => ['required', Rule::in(['private', 'public', 'confessional'])],
            'website'                  => ['nullable', 'url', 'max:255'],
            'document_header'          => ['nullable', 'string', 'max:255'],
            'document_footer'          => ['nullable', 'string'],
            'is_active'                => ['sometimes', 'boolean'],

            // Configuration des pivots
            'cycles'                   => ['nullable', 'array'],
            'cycles.*'                 => ['exists:cycles,id'],
            'series'                   => ['nullable', 'array'],
            'series.*'                 => ['exists:series,id'],
            'levels'                   => ['nullable', 'array'],
            'levels.*'                 => ['exists:levels,id'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            if ($school->logo_path && Storage::disk('public')->exists($school->logo_path)) {
                Storage::disk('public')->delete($school->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('schools/logos', 'public');
        }

        if ($request->hasFile('stamp')) {
            if ($school->stamp_path && Storage::disk('public')->exists($school->stamp_path)) {
                Storage::disk('public')->delete($school->stamp_path);
            }
            $validated['stamp_path'] = $request->file('stamp')->store('schools/stamps', 'public');
        }

        DB::transaction(function () use ($school, $validated, $request) {
            $school->update($validated);

            // Synchronisation des pivots
            $school->cycles()->sync($request->input('cycles', []));
            $school->series()->sync($request->input('series', []));
            $school->levels()->sync($request->input('levels', []));
        });

        return redirect()->route('organisation.schools.show', $school)
                         ->with('success', 'Établissement mis à jour avec succès.');
    }

    /**
     * Change l'année académique courante depuis une vue web.
     */
    public function setCurrentAcademicYear(Request $request, School $school): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
        ]);

        $school->update([
            'current_academic_year_id' => $validated['academic_year_id'],
        ]);

        return redirect()->back()->with('success', 'Année académique active modifiée.');
    }

    /**
     * Bascule le statut d'activation (Actif / Inactif).
     */
    public function toggleActive(School $school): RedirectResponse
    {
        $school->update([
            'is_active' => !$school->is_active,
        ]);

        return redirect()->back()->with('success', 'Statut de l\'établissement mis à jour.');
    }

    /**
     * Supprime un établissement.
     */
    public function destroy(School $school): RedirectResponse
    {
        $school->delete();

        return redirect()->route('organisation.schools.index')
                         ->with('success', 'Établissement supprimé avec succès.');
    }
}
<?php

namespace App\Modules\School\Http\Controllers\Web\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\AcademicPeriod;
use App\Modules\School\Models\AcademicYear;
use App\Modules\School\Models\PeriodTypeItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AcademicPeriodController extends Controller
{
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->loadMissing(['personne.staff', 'roles']);

        // Vérifie si l'utilisateur a un profil admin/superadmin ou n'a pas de school_id rattaché
        $isAdmin = $user->hasRole('admin-ecole') || $user->hasRole('super-admin') || $user->is_admin;
        $schoolId = $user->school_id;

        $query = AcademicPeriod::with(['academicYear', 'periodTypeItem.periodType', 'school']);

        // Si ce n'est pas un Admin (ou si un filtre d'école spécifique n'est pas appliqué)
        if (!$isAdmin) {
            $query->where('school_id', $schoolId);
        } elseif ($request->filled('school_id')) {
            // Possibilité pour l'admin de filtrer par un établissement précis
            $query->where('school_id', $request->school_id);
        }

        $periods = $query->latest()->paginate(15);
        $schools = $isAdmin ? \App\Modules\School\Models\School::all() : collect();

        return view('School::academique.periods.index', compact('periods', 'schools', 'isAdmin'));
    }

    public function create(): View
    {
        $user = auth()->user();
        $user->loadMissing('personne.staff');

        $isAdmin = $user->hasRole('admin-ecole') || $user->hasRole('super-admin') || $user->is_admin;
        
        // Années académiques
        $academicYears = AcademicYear::orderByDesc('id')->get();

        // 1. Types parents (Trimestre, Semestre, etc.) pour le select principal
        $periodTypes = \App\Modules\School\Models\PeriodType::orderBy('name')->get();

        // 2. Éléments ordonnés avec les colonnes nécessaires pour le filtrage JS
        $periodTypeItems = PeriodTypeItem::orderBy('sequence_order')
            ->get(['id', 'period_type_id', 'name', 'code', 'sequence_order']);

        // Établissements
        $schools = $isAdmin ? \App\Modules\School\Models\School::all() : collect();

        return view('School::academique.periods.create', compact(
            'academicYears',
            'periodTypes',
            'periodTypeItems',
            'schools',
            'isAdmin'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'nullable|exists:schools,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'current_period_index' => 'nullable|integer',
            'periods' => 'required|array|min:1',
            'periods.*.period_type_item_id' => 'required|exists:period_type_items,id',
            'periods.*.start_date' => 'required|date',
            'periods.*.end_date' => 'required|date|after_or_equal:periods.*.start_date',
            'periods.*.is_closed' => 'nullable|boolean',
        ]);

        $schoolId = $validated['school_id'] ?? auth()->user()->school_id;
        $currentIndex = $request->input('current_period_index');

        DB::transaction(function () use ($validated, $schoolId, $currentIndex) {
            foreach ($validated['periods'] as $index => $periodData) {
                AcademicPeriod::create([
                    'school_id' => $schoolId,
                    'academic_year_id' => $validated['academic_year_id'],
                    'period_type_item_id' => $periodData['period_type_item_id'],
                    'start_date' => $periodData['start_date'],
                    'end_date' => $periodData['end_date'],
                    'is_current' => ((string)$currentIndex === (string)$index),
                    'is_closed' => !empty($periodData['is_closed']),
                ]);
            }
        });

        return redirect()->back()->with('success', __('Les périodes ont été configurées avec succès.'));
    }

    public function show(AcademicPeriod $period): View
    {
        $period->load(['academicYear', 'periodTypeItem.periodType', 'school']);

        return view('School::academique.periods.show', compact('period'));
    }

    public function edit(AcademicPeriod $period): View
    {
        $user = auth()->user();
        $user->loadMissing('personne.staff');

        $isAdmin = $user->hasRole('admin-ecole') || $user->hasRole('super-admin') || $user->is_admin;
        
        // Années académiques
        $academicYears = AcademicYear::orderByDesc('id')->get();

        // 1. Types parents (Trimestre, Semestre, etc.)
        $periodTypes = \App\Modules\School\Models\PeriodType::orderBy('name')->get();

        // 2. Éléments ordonnés pour le filtrage JS
        $periodTypeItems = PeriodTypeItem::orderBy('sequence_order')
            ->get(['id', 'period_type_id', 'name', 'code', 'sequence_order']);

        // Établissements
        $schools = $isAdmin ? \App\Modules\School\Models\School::all() : collect();

        // Charger les relations nécessaires de la période éditée (y compris le type parent)
        $period->loadMissing(['school', 'academicYear', 'periodTypeItem.periodType']);

        return view('School::academique.periods.edit', compact(
            'period',
            'academicYears',
            'periodTypes',
            'periodTypeItems',
            'schools',
            'isAdmin'
        ));
    }

    public function update(Request $request, AcademicPeriod $period): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $schoolId = $request->input('school_id') ?? $user->school_id ?? $period->school_id;

        $validated = $request->validate([
            'school_id'           => ['required', 'exists:schools,id'],
            'academic_year_id'    => ['required', 'exists:academic_years,id'],
            'period_type_item_id' => [
                'required',
                'exists:period_type_items,id',
                Rule::unique('academic_periods')
                    ->where(fn ($q) => 
                        $q->where('school_id', $schoolId)
                        ->where('academic_year_id', $request->academic_year_id)
                    )->ignore($period->id),
            ],
            'start_date'          => ['required', 'date'],
            'end_date'            => ['required', 'date', 'after:start_date'],
            'is_current'          => ['nullable', 'boolean'],
            'is_closed'           => ['nullable', 'boolean'],
        ]);

        $validated['is_current'] = $request->boolean('is_current');
        $validated['is_closed']  = $request->boolean('is_closed');

        $period->update($validated);

        if ($period->is_current) {
            $period->markAsCurrent();
        }

        return redirect()->route('organisation.periods.index')
            ->with('success', 'Période académique mise à jour avec succès.');
    }

    /*public function setCurrent(AcademicPeriod $period): RedirectResponse
    {
        try {
            $period->markAsCurrent();

            // Récupérer le nom propre via la relation ou l'attribut
            $periodName = $period->periodTypeItem?->name ?? 'Sélectionnée';

            return redirect()->back()->with('success', "La période « {$periodName} » est désormais la période courante.");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', "Impossible de définir la période courante : " . $e->getMessage());
        }
    }*/

    public function setCurrent(AcademicPeriod $period)
    {
        // 1. Réinitialiser toutes les périodes de la MÊME école (et/ou de la même année) à is_current = 0
        AcademicPeriod::where('school_id', $period->school_id)
            // Optionnel : restreindre aussi par année si vous autorisez 1 période courante par année académique
            // ->where('academic_year_id', $period->academic_year_id) 
            ->update(['is_current' => false]);

        // 2. Activer uniquement la période sélectionnée
        $period->update(['is_current' => true]);

        return redirect()->back()->with('success', 'La période courante a été mise à jour avec succès.');
    }

    /**
     * Verrouille ou déverrouille la période.
     */
    public function toggleClosed(AcademicPeriod $period): RedirectResponse
    {
        try {
            $period->is_closed = !$period->is_closed;
            $period->save();

            $action = $period->is_closed ? 'clôturée' : 'rouverte';

            return redirect()->back()->with('success', "La période a été {$action} avec succès.");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', "Erreur lors de la modification du statut : " . $e->getMessage());
        }
    }

    public function destroy(AcademicPeriod $period): RedirectResponse
    {
        $period->delete();

        return redirect()->route('organisation.periods.index')
                         ->with('success', 'Période académique supprimée avec succès.');
    }
}
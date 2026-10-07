<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\FeePlan;
use App\Modules\School\Models\FeePlanItem;
use App\Modules\School\Models\AcademicYear;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\Level;
use App\Modules\School\Models\School;
use App\Modules\School\Models\Registration;
use App\Modules\School\Models\StudentFeeAccount;
use App\Modules\School\Models\StudentFeeSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class FeePlanController extends Controller
{
    public function index(Request $request)
    {
        // Récupération de toutes les années pour le filtre
        $academicYears = AcademicYear::all();

        $query = FeePlan::with(['academicYear', 'level', 'schoolClass', 'items']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $feePlans = $query->latest()->paginate(10);

        return view('School::accounting.fee_plans.index', compact('feePlans', 'academicYears'));
    }

    public function create()
    {
        // Utilisation de is_current au lieu de is_active
        $academicYears = AcademicYear::where('is_current', true)->get();
        
        // Si aucune année n'est marquée is_current, charger toutes les années par sécurité
        if ($academicYears->isEmpty()) {
            $academicYears = AcademicYear::all();
        }

        $levels = Level::join('level_school','level_school.level_id','levels.id')->get();
        $classes = SchoolClass::all();
        $schools = School::all();

        return view('School::accounting.fee_plans.create', compact('academicYears', 'levels', 'classes', 'schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name'             => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'level_id'         => 'nullable|exists:levels,id',
            'school_class_id'  => 'nullable|exists:school_classes,id',
            'total_amount'     => 'required|numeric|min:0',
            
            // Validation du tableau d'échéances
            'items'              => 'required|array|min:1',
            'items.*.label'       => 'required|string|max:255',
            'items.*.amount'      => 'required|numeric|min:0',
            'items.*.due_date'    => 'required|date',
            'items.*.is_blocking' => 'nullable|boolean',
        ]);

        // Vérification que la somme des tranches correspond au total du plan
        $itemsTotal = array_sum(array_column($validated['items'], 'amount'));
        if ((float)$itemsTotal !== (float)$validated['total_amount']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['total_amount' => 'La somme des échéances (' . number_format($itemsTotal, 0, ',', ' ') . ' FCFA) ne correspond pas au montant total (' . number_format($validated['total_amount'], 0, ',', ' ') . ' FCFA).']);
        }

        DB::transaction(function () use ($validated) {
            //$schoolId = auth()->user()->school_id ?? 1; // Ajuster selon ton contexte tenant/école

            // 1. Enregistrement de l'en-tête FeePlan
            $feePlan = FeePlan::create([
                'school_id'        => $validated['school_id'],
                'academic_year_id' => $validated['academic_year_id'],
                'level_id'         => $validated['level_id'] ?? null,
                'school_class_id'  => $validated['school_class_id'] ?? null,
                'name'             => $validated['name'],
                'total_amount'     => $validated['total_amount'],
                'is_active'        => true,
            ]);

            // 2. Enregistrement des tranches (FeePlanItems)
            foreach ($validated['items'] as $index => $item) {
                FeePlanItem::create([
                    'fee_plan_id' => $feePlan->id,
                    'label'       => $item['label'],
                    'amount'      => $item['amount'],
                    'due_date'    => $item['due_date'],
                    'position'    => $index + 1,
                    'is_blocking' => isset($item['is_blocking']) ? (bool)$item['is_blocking'] : true,
                ]);
            }
        });

        return redirect()->route('school.fee-plans.index')->with('success', 'Plan tarifaire créé avec succès.');
    }

    /**
     * Affiche les détails d'un plan tarifaire (pour la modale AJAX).
     */
    public function show(FeePlan $feePlan)
    {
        $feePlan->load(['academicYear', 'level', 'schoolClass', 'items']);

        return view('School::accounting.fee_plans.show', compact('feePlan'));
    }

    /**
     * Affiche le formulaire d'édition (Vue modale AJAX)
     */
    public function edit($id)
    {
        $feePlan = FeePlan::with('items')->findOrFail($id);
        
        $schools = School::all();
        $academicYears = AcademicYear::all();
        $levels = Level::all();
        $classes = SchoolClass::all();

        return view('school::accounting.fee_plans.edit', compact('feePlan', 'schools', 'academicYears', 'levels', 'classes'));
    }

    /**
     * Met à jour le plan tarifaire et ses tranches
     */
    public function update(Request $request, $id)
    {
        $feePlan = FeePlan::findOrFail($id);

        $validated = $request->validate([
            'school_id'        => 'required|exists:schools,id',
            'name'             => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'level_id'         => 'nullable|exists:levels,id',
            'school_class_id'  => 'nullable|exists:school_classes,id',
            'total_amount'     => 'required|numeric|min:0',
            'items'            => 'required|array|min:1',
            'items.*.label'    => 'required|string|max:255',
            'items.*.amount'   => 'required|numeric|min:0',
            'items.*.due_date' => 'required|date',
            'items.*.is_blocking' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($feePlan, $validated, $request) {
            // 1. Mise à jour de l'entête
            $feePlan->update([
                'school_id'        => $validated['school_id'],
                'name'             => $validated['name'],
                'academic_year_id' => $validated['academic_year_id'],
                'level_id'         => $validated['level_id'] ?? null,
                'school_class_id'  => $validated['school_class_id'] ?? null,
                'total_amount'     => $validated['total_amount'],
            ]);

            // 2. Synchronisation des tranches (Suppression des anciennes et réinsertion)
            $feePlan->items()->delete();

            foreach ($request->items as $item) {
                $feePlan->items()->create([
                    'label'       => $item['label'],
                    'amount'      => $item['amount'],
                    'due_date'    => $item['due_date'],
                    'is_blocking' => isset($item['is_blocking']) ? 1 : 0,
                ]);
            }
        });

        return redirect()->route('school.fee-plans.index')->with('success', 'Le plan tarifaire a été mis à jour avec succès.');
    }

    public function syncStudents(FeePlan $feePlan)
    {
        // Charger les tranches du plan
        $feePlan->load('items');

        if ($feePlan->items->isEmpty()) {
            return redirect()->back()->with('error', 'Ce plan tarifaire ne contient aucune tranche à synchroniser.');
        }

        // Récupérer toutes les inscriptions correspondant au plan (Classe spécifique ou Globale)
        $registrations = Registration::where('school_id', $feePlan->school_id)
            ->where('academic_year_id', $feePlan->academic_year_id)
            ->when($feePlan->school_class_id, function ($q) use ($feePlan) {
                $q->where('school_class_id', $feePlan->school_class_id);
            })
            ->get();

        $createdSchedulesCount = 0;

        DB::transaction(function () use ($registrations, $feePlan, &$createdSchedulesCount) {
            foreach ($registrations as $registration) {
                // 1. Récupérer ou créer le compte financier de l'étudiant
                $account = StudentFeeAccount::firstOrCreate(
                    ['registration_id' => $registration->id],
                    [
                        'school_id'       => $registration->school_id,
                        'student_id'      => $registration->student_id,
                        'fee_plan_id'     => $feePlan->id,
                        'total_due'       => 0,
                        'discount_amount' => 0,
                        'total_paid'      => 0,
                        'balance'         => 0,
                        'status'          => 'unpaid',
                    ]
                );

                // 2. Parcourir les tranches du plan tarifaire
                foreach ($feePlan->items as $item) {
                    // Vérifier si la tranche existe déjà dans le compte de l'étudiant
                    $exists = StudentFeeSchedule::where('student_fee_account_id', $account->id)
                        ->where('fee_plan_item_id', $item->id)
                        ->exists();

                    if (!$exists) {
                        StudentFeeSchedule::create([
                            'student_fee_account_id' => $account->id,
                            'fee_plan_item_id'       => $item->id,
                            'label'                  => $feePlan->name . ' - ' . $item->label,
                            'amount'                 => $item->amount,
                            'paid_amount'            => 0.00,
                            'due_date'               => $item->due_date,
                            'is_paid'                => false,
                            'is_blocking'            => $item->is_blocking ?? false,
                        ]);

                        // Recalculer le total dû et le solde du compte financier
                        $account->total_due += $item->amount;
                        $account->balance = max(0, $account->total_due - $account->discount_amount - $account->total_paid);

                        // Mettre à jour le statut du compte
                        if ($account->balance == 0 && $account->total_due > 0) {
                            $account->status = 'paid';
                        } elseif ($account->total_paid > 0) {
                            $account->status = 'partially_paid';
                        } else {
                            $account->status = 'unpaid';
                        }

                        $account->save();
                        $createdSchedulesCount++;
                    }
                }
            }
        });

        return redirect()->back()->with(
            'success',
            "Le plan tarifaire a été appliqué à {$registrations->count()} étudiant(s) ({$createdSchedulesCount} tranche(s) générée(s))."
        );
    }

    /**
     * Bascule le statut actif/inactif d'un plan tarifaire.
     */
    public function toggleActive(FeePlan $feePlan)
    {
        $feePlan->update([
            'is_active' => !$feePlan->is_active,
        ]);

        $statusMessage = $feePlan->is_active ? 'activé' : 'désactivé';

        return redirect()->back()->with('success', "Le plan tarifaire a été {$statusMessage} avec succès.");
    }
}
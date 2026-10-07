<?php

namespace App\Modules\School\Http\Controllers\Web\Schedule;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Country;
use App\Modules\School\Models\Degree;
use App\Modules\School\Models\Personne;
use App\Modules\School\Models\School;
use App\Modules\School\Models\Speciality;
use App\Modules\School\Models\Staff;
use App\Modules\School\Models\StaffContract;
use App\Modules\School\Models\StaffRole;
use App\Modules\School\Http\Requests\StoreStaffRequest;
use App\Modules\School\Http\Requests\UpdateStaffRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf; // Si vous utilisez barryvdh/laravel-dompdf
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index(): View
    {
        $staffs = Staff::with(['personne', 'school', 'speciality', 'degree', 'activeContracts.role'])->get();
        return view('School::schedule.staff.index', compact('staffs'));
    }

    public function create(): View
    {
        $schools      = School::all();
        $roles        = StaffRole::all();
        $specialities = Speciality::all();
        $degrees      = Degree::all();
        $countries    = Country::all();

        return view('School::schedule.staff.create', compact('schools', 'roles', 'specialities', 'degrees', 'countries'));
    }

    public function store(StoreStaffRequest $request): RedirectResponse
    {
        
       DB::transaction(function () use ($request) {
            // Traitement sécurisé de la photo de profil (Stockage local privé)
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'local');
            }

            $personne = Personne::create([
                'nom'         => $request->validated('nom'), // Le Mutateur d'Attribute dans Personne gère la mise en majuscule
                'prenoms'     => $request->validated('prenoms'),
                'sexe'        => $request->validated('sexe'),
                'civility'    => $request->validated('civility'),
                'sit_mat'     => $request->validated('sit_mat'),
                'birth_date'  => $request->validated('birth_date'),
                'birth_place' => $request->validated('birth_place'),
                'country_id'  => $request->validated('country_id'),
                'telephone'   => $request->validated('telephone'),
                'email'       => $request->validated('email'),
                'photo'       => $photoPath,
            ]);

            $staff = $personne->staff()->create([
                'school_id'     => $request->validated('school_id'),
                'staff_code'    => $request->validated('staff_code'),
                'speciality_id' => $request->validated('speciality_id'),
                'degree_id'     => $request->validated('degree_id'),
                'is_active'     => $request->boolean('is_active', true),
            ]);

            $contractSchoolId = $request->validated('contract_school_id', $staff->school_id);
            $documentPath = null;
            if ($request->hasFile('contract_document')) {
                $documentPath = $request->file('contract_document')
                    ->store('contracts/' . $contractSchoolId, 'local');
            }

            $staff->contracts()->create([
                'school_id'              => $contractSchoolId,
                'staff_role_id'          => $request->validated('staff_role_id'),
                'job_title'              => $request->validated('job_title'),
                'contract_type'          => $request->validated('contract_type'),
                'start_date'             => $request->validated('start_date'),
                'end_date'               => $request->validated('end_date'),
                'pay_type'               => $request->validated('pay_type'),
                'base_salary_or_rate'    => $request->validated('base_salary_or_rate'),
                'contract_document_path' => $documentPath,
                'status'                 => StaffContract::STATUS_ACTIVE,
            ]);
        });

        return redirect()->route('schedule.staff.index')->with('success', 'Membre du personnel enregistré et contrat établi avec succès.');
    }

    public function show(Staff $staff): View
    {
        $staff->load(['personne', 'school', 'speciality', 'degree', 'contracts.role', 'contracts.school']);
        return view('School::schedule.staff.show', compact('staff'));
    }

    public function edit(Staff $staff): View
    {
        $staff->load(['personne', 'contracts' => function ($query) {
            $query->where('status', StaffContract::STATUS_ACTIVE)->latest()->take(1);
        }]);

        $schools      = School::all();
        $roles        = StaffRole::all();
        $specialities = Speciality::all();
        $degrees      = Degree::all();
        $countries    = Country::all();

        return view('School::schedule.staff.edit', compact('staff', 'schools', 'roles', 'specialities', 'degrees', 'countries'));
    }

    public function update(UpdateStaffRequest $request, Staff $staff): RedirectResponse
    {             
        DB::transaction(function () use ($request, $staff) {
            $personne = $staff->personne;
            $photoPath = $personne->photo;

            // Remplacement sécurisé de la photo si un nouveau fichier est transmis
            if ($request->hasFile('photo')) {
                // Suppression du fichier précédent du storage local privé s'il existe
                if ($photoPath && Storage::disk('local')->exists($photoPath)) {
                    Storage::disk('local')->delete($photoPath);
                }
                $photoPath = $request->file('photo')->store('photos', 'local');
            }

            // Update Personne
            $personne->update([
                'nom'         => $request->validated('nom'),
                'prenoms'     => $request->validated('prenoms'),
                'sexe'        => $request->validated('sexe'),
                'civility'    => $request->validated('civility'),
                'sit_mat'     => $request->validated('sit_mat'),
                'birth_date'  => $request->validated('birth_date'),
                'birth_place' => $request->validated('birth_place'),
                'country_id'  => $request->validated('country_id'),
                'telephone'   => $request->validated('telephone'),
                'email'       => $request->validated('email'),
                'photo'       => $photoPath,
            ]);

            // Update Staff
            $staff->update([
                'school_id'     => $request->validated('school_id'),
                'staff_code'    => $request->validated('staff_code'),
                'speciality_id' => $request->validated('speciality_id'),
                'degree_id'     => $request->validated('degree_id'),
                'is_active'     => $request->boolean('is_active', true),
            ]);
        });

        return redirect()->route('schedule.staff.index')->with('success', 'Informations du personnel mises à jour avec succès.');
    }

    public function destroy(Staff $staff): RedirectResponse
    {
        DB::transaction(function () use ($staff) {
            // Suppression des fichiers de contrats associés
            foreach ($staff->contracts as $contract) {
                if ($contract->contract_document_path && Storage::disk('local')->exists($contract->contract_document_path)) {
                    Storage::disk('local')->delete($contract->contract_document_path);
                }
            }
            $staff->contracts()->delete();

            $personne = $staff->personne;

            // Suppression de la photo sur le disque local
            if ($personne && $personne->photo && Storage::disk('local')->exists($personne->photo)) {
                Storage::disk('local')->delete($personne->photo);
            }

            $staff->delete();

            if ($personne) {
                $personne->delete();
            }
        });

        return redirect()->route('schedule.staff.index')->with('success', 'Membre du personnel supprimé avec succès.');
    }

    public function exportPdf($id)
    {
        $staff = Staff::with(['personne', 'contracts.school', 'speciality', 'degree'])->findOrFail($id);

        // Génération du PDF à partir d'une vue dédiée
        $pdf = Pdf::loadView('School::schedule.staff.fiche_personnel', compact('staff'))
                ->setPaper('a4', 'portrait');

        // Affichage direct dans le navigateur (ou ->download('fiche-personnel.pdf'))
        return $pdf->stream('fiche_personnel_' . $staff->staff_code . '.pdf');
    }
}
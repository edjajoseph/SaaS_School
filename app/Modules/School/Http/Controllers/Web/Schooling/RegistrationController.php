<?php

namespace App\Modules\School\Http\Controllers\Web\Schooling;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

// Modèles du système
use App\Modules\School\Models\Registration;
use App\Modules\School\Models\Student;
use App\Modules\School\Models\Personne;
use App\Modules\School\Models\School;
use App\Modules\School\Models\AcademicYear;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\StudentDocument;
use App\Modules\School\Models\DocumentType;
use App\Modules\School\Models\Country;
use App\Modules\School\Models\FeePlan;
use App\Modules\School\Models\StudentFeeAccount;
use App\Modules\School\Models\StudentFeeSchedule;
use App\Modules\School\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Notifications\AccountActivationNotification;

class RegistrationController extends Controller
{
    /**
     * Liste des inscriptions administratives
     */
    public function index(Request $request)
    {
        $query = Registration::with(['student.personne', 'school', 'academicYear', 'schoolClass']);

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($qStudent) use ($search) {
                      $qStudent->where('matricule', 'like', "%{$search}%")
                               ->orWhereHas('personne', function ($qPers) use ($search) {
                                   $qPers->where('nom', 'like', "%{$search}%")
                                         ->orWhere('prenoms', 'like', "%{$search}%");
                               });
                  });
            });
        }

        $registrations = $query->latest()->paginate(15);
        $schools = School::all();
        $academicYears = AcademicYear::all();

        return view('School::schooling.registrations.index', compact('registrations', 'schools', 'academicYears'));
    }

    /**
     * Formulaire de création d'inscription
     */
    public function create()
    {
        $schools       = School::all();
        $academicYears = AcademicYear::all();
        $classes       = SchoolClass::all();
        $students      = Student::with('personne')->get();
        $documentTypes = DocumentType::where('is_active', true)->get();
        $countries     = Country::all();

        return view('School::schooling.registrations.create', compact('schools', 'academicYears', 'classes', 'students', 'documentTypes', 'countries'));
    }

    /**
     * Inscription complète + Génération automatique du compte financier et des échéances
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validation des données
        $rules = [
            'type'              => 'required|string|in:inscription,reinscription',
            'school_id'         => 'required|exists:schools,id',
            'academic_year_id'  => 'required|exists:academic_years,id',
            'school_class_id'   => 'required|exists:school_classes,id',
            'registration_date' => 'required|date',
            'status'            => 'required|string|in:confirmed,pending',
            'discount_amount'   => 'nullable|numeric|min:0',
            'discount_reason'   => 'nullable|string|max:255',
            'payment_receipt'   => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
            'notes'             => 'nullable|string',

            'student_id'        => 'required_if:type,reinscription|nullable|exists:students,id',

            // Champs Identité Étudiant
            'matricule'         => 'nullable|string|max:50|unique:students,matricule',
            'nationality'       => 'nullable|string|max:100',
            'nom'               => 'nullable|string|max:255',
            'prenoms'           => 'nullable|string|max:255',
            'sexe'              => 'nullable|in:M,F',
            'birth_date'        => 'nullable|date',
            'birth_place'       => 'nullable|string|max:255',
            'country_id'        => 'nullable|exists:countries,id',
            'telephone'         => 'nullable|string|max:50',
            'email'             => 'nullable|email|unique:personnes,email|unique:users,email',
            'address'           => 'nullable|string|max:255',
            'photo'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Tuteurs et Parents
            'father_name'       => 'nullable|string|max:150',
            'father_job'        => 'nullable|string|max:150',
            'father_phone'      => 'nullable|string|max:50',
            'mother_name'       => 'nullable|string|max:150',
            'mother_job'        => 'nullable|string|max:150',
            'mother_phone'      => 'nullable|string|max:50',
            'guardian_name'     => 'nullable|string|max:150',
            'guardian_relation' => 'nullable|string|max:100',
            'guardian_phone'    => 'nullable|string|max:50',

            // Documents
            'documents'                    => 'nullable|array',
            'documents.*.document_type_id' => 'required_with:documents.*.title|exists:document_types,id',
            'documents.*.title'            => 'nullable|string|max:255',
            'documents.*.file'             => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
        ];

        if ($request->input('type') === 'reinscription') {
            $rules['student_id'] = [
                'required',
                'exists:students,id',
                Rule::unique('registrations')->where(function ($query) use ($request) {
                    return $query->where('school_id', $request->input('school_id'))
                                ->where('academic_year_id', $request->input('academic_year_id'));
                }),
            ];
        } else {
            $rules['nom']     = ['required', 'string', 'max:255'];
            $rules['prenoms'] = ['required', 'string', 'max:255'];
            $rules['sexe']    = ['required', Rule::in(['M', 'F'])];
        }

        $validated = $request->validate($rules);

        // Variable pour capturer l'utilisateur créé dans la transaction
        $createdUser = null;

        // 2. Traitement sous transaction SQL
        DB::transaction(function () use ($request, $validated, &$createdUser) {
            $studentId = null;

            if ($request->input('type') === 'inscription') {
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store('students/photos', 'public');
                }

                $personne = Personne::create([
                    'nom'         => $validated['nom'],
                    'prenoms'     => $validated['prenoms'],
                    'sexe'        => $validated['sexe'],
                    'birth_date'  => $validated['birth_date'] ?? null,
                    'birth_place' => $validated['birth_place'] ?? null,
                    'country_id'  => $validated['country_id'] ?? null,
                    'telephone'   => $validated['telephone'] ?? null,
                    'email'       => $validated['email'] ?? null,
                    'address'     => $validated['address'] ?? null,
                    'photo'       => $photoPath,
                ]);

                $matricule = !empty($validated['matricule']) 
                    ? $validated['matricule'] 
                    : 'MAT-' . date('Y') . '-' . str_pad(Student::count() + 1, 4, '0', STR_PAD_LEFT);

                $student = Student::create([
                    'personne_id'       => $personne->id,
                    'matricule'         => $matricule,
                    'nationality'       => $validated['nationality'] ?? null,
                    'father_name'       => $validated['father_name'] ?? null,
                    'father_job'        => $validated['father_job'] ?? null,
                    'father_phone'      => $validated['father_phone'] ?? null,
                    'mother_name'       => $validated['mother_name'] ?? null,
                    'mother_job'        => $validated['mother_job'] ?? null,
                    'mother_phone'      => $validated['mother_phone'] ?? null,
                    'guardian_name'     => $validated['guardian_name'] ?? null,
                    'guardian_relation' => $validated['guardian_relation'] ?? null,
                    'guardian_phone'    => $validated['guardian_phone'] ?? null,
                ]);

                $studentId = $student->id;

                // --- CRÉATION AUTOMATIQUE DU COMPTE UTILISATEUR ETUDIANT ---
                if (!empty($validated['email'])) {
                    $createdUser = User::create([
                        'personne_id' => $personne->id,
                        'name'        => trim($personne->nom . ' ' . $personne->prenoms),
                        'email'       => $validated['email'],
                        'password'    => Hash::make('Password123!'),
                        'pwd_change'  => false,
                        'isactive'    => false,
                    ]);

                    // Attachement du rôle "etudiant" (correspondant au nom système dans la table roles)
                    $createdUser->addRole('etudiant'); // ou $createdUser->syncRoles(['etudiant']);
                }

            } else {
                $studentId = $validated['student_id'];
            }

            $receiptPath = null;
            if ($request->hasFile('payment_receipt')) {
                $receiptPath = $request->file('payment_receipt')->store('registrations/receipts', 'public');
            }

            // Création de l'inscription administrative
            $registration = Registration::create([
                'school_id'           => $validated['school_id'],
                'academic_year_id'    => $validated['academic_year_id'],
                'student_id'          => $studentId,
                'school_class_id'     => $validated['school_class_id'],
                'registration_number' => Registration::generateRegistrationNumber(),
                'type'                => $validated['type'],
                'status'              => $validated['status'],
                'payment_receipt'     => $receiptPath,
                'registration_date'   => $validated['registration_date'],
                'notes'               => $validated['notes'] ?? null,
            ]);

            // 3. Intégration Financière : Détection de TOUS les Plans Tarifaires applicables
            $feePlans = FeePlan::with('items')
                ->where('school_id', $registration->school_id)
                ->where('academic_year_id', $registration->academic_year_id)
                ->where(function ($q) use ($registration) {
                    $q->where('school_class_id', $registration->school_class_id)
                    ->orWhereNull('school_class_id');
                })
                ->where('is_active', true)
                ->get();

            // Calcule le cumul de tous les plans
            $totalDue       = $feePlans->sum('total_amount');
            $discountAmount = $validated['discount_amount'] ?? 0.00;
            $balance        = max(0, $totalDue - $discountAmount);

            // 4. Création du compte de frais de l'étudiant
            $feeAccount = StudentFeeAccount::create([
                'school_id'       => $registration->school_id,
                'registration_id' => $registration->id,
                'student_id'      => $studentId,
                'fee_plan_id'     => $feePlans->first()?->id,
                'total_due'       => $totalDue,
                'discount_amount' => $discountAmount,
                'discount_reason' => $validated['discount_reason'] ?? null,
                'total_paid'      => 0.00,
                'balance'         => $balance,
                'status'          => 'unpaid',
            ]);

            // 5. Génération dynamique des tranches d'échéancier pour TOUS les plans
            foreach ($feePlans as $plan) {
                foreach ($plan->items as $item) {
                    StudentFeeSchedule::create([
                        'student_fee_account_id' => $feeAccount->id,
                        'fee_plan_item_id'       => $item->id,
                        'label'                  => $plan->name . ' - ' . $item->label,
                        'amount'                 => $item->amount,
                        'paid_amount'            => 0.00,
                        'due_date'               => $item->due_date,
                        'is_paid'                => false,
                        'is_blocking'            => $item->is_blocking ?? false,
                    ]);
                }
            }

            // 6. Gestion des documents joints
            if ($request->has('documents') && is_array($request->input('documents'))) {
                foreach ($request->input('documents') as $index => $docData) {
                    if (empty($docData['title']) || empty($docData['document_type_id'])) {
                        continue;
                    }

                    $filePath = null;
                    if ($request->hasFile("documents.{$index}.file")) {
                        $filePath = $request->file("documents.{$index}.file")->store('students/documents', 'public');
                    }

                    StudentDocument::create([
                        'student_id'       => $studentId,
                        'registration_id'  => $registration->id,
                        'document_type_id' => $docData['document_type_id'],
                        'title'            => $docData['title'],
                        'is_provided'      => isset($docData['is_provided']) ? (bool) $docData['is_provided'] : false,
                        'file_path'        => $filePath,
                        'remarks'          => $docData['remarks'] ?? null,
                    ]);
                }
            }
        });

        // 7. Envoi de l'email d'activation en dehors de la transaction
        if ($createdUser) {
            $createdUser->notify(new AccountActivationNotification());
        }

        return redirect()->route('schooling.registrations.index')
                        ->with('success', 'Inscription administrative enregistrée, compte étudiant créé et lien d’activation envoyé avec succès.');
    }

    /**
     * Détails d'une inscription
     */
    public function show(Registration $registration)
    {
        $registration->load([
            'student.personne', 
            'school', 
            'academicYear', 
            'schoolClass', 
            'documents.documentType',
            'feeAccount.schedules'
        ]);
        
        return view('School::schooling.registrations.show', compact('registration'));
    }

    /**
     * Édition de l'inscription
     */
    public function edit(Registration $registration)
    {
        $registration->load(['student.personne', 'documents.documentType', 'school', 'academicYear', 'schoolClass']);

        $schools       = School::all();
        $academicYears = AcademicYear::all();
        $classes       = SchoolClass::all();
        $students      = Student::with('personne')->get();
        $documentTypes = DocumentType::where('is_active', true)->get();
        $countries     = Country::all();

        return view('School::schooling.registrations.edit', compact(
            'registration', 
            'schools', 
            'academicYears', 
            'classes', 
            'students',
            'documentTypes',
            'countries'
        ));
    }

    /**
     * Mise à jour de l'inscription
     */
    public function update(Request $request, Registration $registration): RedirectResponse
    {
        $student = $registration->student;

        $rules = [
            'type'              => 'required|string|in:inscription,reinscription',
            'school_id'         => 'required|exists:schools,id',
            'academic_year_id'  => 'required|exists:academic_years,id',
            'school_class_id'   => 'required|exists:school_classes,id',
            'registration_date' => 'required|date',
            'status'            => 'required|string|in:confirmed,pending,canceled,transferred',
            'payment_receipt'   => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
            'notes'             => 'nullable|string',

            'student_id'        => 'required_if:type,reinscription|nullable|exists:students,id',

            // Champs Étudiant
            'matricule'         => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('students', 'matricule')->ignore($student?->id),
            ],
            'nationality'       => 'nullable|string|max:100',
            'nom'               => 'nullable|string|max:255',
            'prenoms'           => 'nullable|string|max:255',
            'sexe'              => 'nullable|in:M,F',
            'birth_date'        => 'nullable|date',
            'birth_place'       => 'nullable|string|max:255',
            'country_id'        => 'nullable|exists:countries,id',
            'telephone'         => 'nullable|string|max:50',
            'email'             => 'nullable|email|max:255',
            'address'           => 'nullable|string|max:255',
            'photo'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Parents
            'father_name'       => 'nullable|string|max:150',
            'father_job'        => 'nullable|string|max:150',
            'father_phone'      => 'nullable|string|max:50',
            'mother_name'       => 'nullable|string|max:150',
            'mother_job'        => 'nullable|string|max:150',
            'mother_phone'      => 'nullable|string|max:50',
            'guardian_name'     => 'nullable|string|max:150',
            'guardian_relation' => 'nullable|string|max:100',
            'guardian_phone'    => 'nullable|string|max:50',

            // Documents
            'documents'                    => 'nullable|array',
            'documents.*.document_type_id' => 'required_with:documents.*.title|exists:document_types,id',
            'documents.*.title'            => 'nullable|string|max:255',
            'documents.*.file'             => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
        ];

        if ($request->input('type') === 'reinscription') {
            $rules['student_id'] = [
                'required',
                'exists:students,id',
                Rule::unique('registrations')->where(function ($query) use ($request) {
                    return $query->where('school_id', $request->input('school_id'))
                                ->where('academic_year_id', $request->input('academic_year_id'));
                })->ignore($registration->id),
            ];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $validated, $registration) {
            $studentId = $registration->student_id;

            if ($validated['type'] === 'inscription') {
                $student = $registration->student;

                if ($student) {
                    if ($request->hasFile('photo')) {
                        if ($student->personne && $student->personne->photo) {
                            Storage::disk('public')->delete($student->personne->photo);
                        }
                        $photoPath = $request->file('photo')->store('students/photos', 'public');
                    } else {
                        $photoPath = $student->personne->photo ?? null;
                    }

                    if ($student->personne) {
                        $student->personne->update([
                            'nom'         => $validated['nom'],
                            'prenoms'     => $validated['prenoms'],
                            'sexe'        => $validated['sexe'],
                            'birth_date'  => $validated['birth_date'] ?? null,
                            'birth_place' => $validated['birth_place'] ?? null,
                            'country_id'  => $validated['country_id'] ?? null,
                            'telephone'   => $validated['telephone'] ?? null,
                            'email'       => $validated['email'] ?? null,
                            'address'     => $validated['address'] ?? null,
                            'photo'       => $photoPath,
                        ]);
                    }

                    $student->update([
                        'matricule'         => $validated['matricule'] ?? $student->matricule,
                        'nationality'       => $validated['nationality'] ?? null,
                        'father_name'       => $validated['father_name'] ?? null,
                        'father_job'        => $validated['father_job'] ?? null,
                        'father_phone'      => $validated['father_phone'] ?? null,
                        'mother_name'       => $validated['mother_name'] ?? null,
                        'mother_job'        => $validated['mother_job'] ?? null,
                        'mother_phone'      => $validated['mother_phone'] ?? null,
                        'guardian_name'     => $validated['guardian_name'] ?? null,
                        'guardian_relation' => $validated['guardian_relation'] ?? null,
                        'guardian_phone'    => $validated['guardian_phone'] ?? null,
                    ]);
                }
            } else {
                $studentId = $validated['student_id'];
            }

            if ($request->hasFile('payment_receipt')) {
                if ($registration->payment_receipt) {
                    Storage::disk('public')->delete($registration->payment_receipt);
                }
                $receiptPath = $request->file('payment_receipt')->store('registrations/receipts', 'public');
            } else {
                $receiptPath = $registration->payment_receipt;
            }

            $registration->update([
                'school_id'         => $validated['school_id'],
                'academic_year_id'  => $validated['academic_year_id'],
                'student_id'        => $studentId,
                'school_class_id'   => $validated['school_class_id'],
                'type'              => $validated['type'],
                'status'            => $validated['status'],
                'payment_receipt'   => $receiptPath,
                'registration_date' => $validated['registration_date'],
                'notes'             => $validated['notes'] ?? null,
            ]);

            // Mise à jour des documents
            if ($request->has('documents') && is_array($request->input('documents'))) {
                foreach ($request->input('documents') as $index => $docData) {
                    if (empty($docData['title']) || empty($docData['document_type_id'])) {
                        continue;
                    }

                    $docId = $docData['id'] ?? null;
                    $existingDoc = $docId ? StudentDocument::find($docId) : null;

                    $filePath = $existingDoc ? $existingDoc->file_path : null;
                    if ($request->hasFile("documents.{$index}.file")) {
                        if ($existingDoc && $existingDoc->file_path) {
                            Storage::disk('public')->delete($existingDoc->file_path);
                        }
                        $filePath = $request->file("documents.{$index}.file")->store('students/documents', 'public');
                    }

                    StudentDocument::updateOrCreate(
                        ['id' => $docId],
                        [
                            'student_id'       => $studentId,
                            'registration_id'  => $registration->id,
                            'document_type_id' => $docData['document_type_id'],
                            'title'            => $docData['title'],
                            'is_provided'      => isset($docData['is_provided']) ? (bool) $docData['is_provided'] : false,
                            'file_path'        => $filePath,
                            'remarks'          => $docData['remarks'] ?? null,
                        ]
                    );
                }
            }
        });

        return redirect()->route('schooling.registrations.index')
                        ->with('success', 'L\'inscription a été mise à jour avec succès.');
    }

    /**
     * Impression de la fiche d'inscription administrative au format PDF
     */
    public function generatePdf($id)
    {
        $registration = Registration::with([
            'student.personne', 
            'school', 
            'academicYear', 
            'schoolClass',
            'feeAccount.schedules',
            'documents.documentType'
        ])->findOrFail($id);

        $data = [
            'registration' => $registration,
            'student'      => $registration->student,
            'personne'     => $registration->student->personne,
            'schoolClass'  => $registration->schoolClass,
            'academicYear' => $registration->academicYear,
            'feeAccount'   => $registration->feeAccount,
            'title'        => 'FICHE D\'INSCRIPTION - ' . $registration->registration_number,
            'date'         => date('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadview('School::schooling.registrations.pdf', $data)
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('Fiche_' . $registration->registration_number . '.pdf');
    }
}
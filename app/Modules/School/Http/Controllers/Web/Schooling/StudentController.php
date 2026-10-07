<?php

namespace App\Modules\School\Http\Controllers\Web\Schooling;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Country; // Ou App\Modules\School\Models\Country selon ton arborescence
use App\Modules\School\Models\Personne;
use App\Modules\School\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['personne.country', 'country', 'enrollments']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                  ->orWhereHas('personne', function ($qp) use ($search) {
                      $qp->where('nom', 'like', "%{$search}%")
                         ->orWhere('prenoms', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('telephone', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        return view('School::schooling.students.index', compact('students'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        return view('School::schooling.students.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Champs Personne (État civil & Contact)
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
            'telephone' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:personnes,email',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Champs Student (Scolarité & Filiation)
            'matricule' => 'required|string|unique:students,matricule',
            'nationality' => 'nullable|exists:countries,id',
            'father_name' => 'nullable|string|max:255',
            'father_job' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:50',
            'mother_name' => 'nullable|string|max:255',
            'mother_job' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:50',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:100',
            'guardian_phone' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($request, $validated, &$student) {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('students/photos', 'public');
            }

            $personne = Personne::create([
                'nom' => $validated['nom'],
                'prenoms' => $validated['prenoms'],
                'sexe' => $validated['sexe'],
                'birth_date' => $validated['birth_date'] ?? null,
                'birth_place' => $validated['birth_place'] ?? null,
                'country_id' => $validated['country_id'] ?? null,
                'telephone' => $validated['telephone'] ?? null,
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'] ?? null,
                'photo' => $photoPath,
            ]);

            $student = Student::create([
                'personne_id' => $personne->id,
                'matricule' => $validated['matricule'],
                'nationality' => $validated['nationality'] ?? null,
                'father_name' => $validated['father_name'] ?? null,
                'father_job' => $validated['father_job'] ?? null,
                'father_phone' => $validated['father_phone'] ?? null,
                'mother_name' => $validated['mother_name'] ?? null,
                'mother_job' => $validated['mother_job'] ?? null,
                'mother_phone' => $validated['mother_phone'] ?? null,
                'guardian_name' => $validated['guardian_name'] ?? null,
                'guardian_relation' => $validated['guardian_relation'] ?? null,
                'guardian_phone' => $validated['guardian_phone'] ?? null,
            ]);
        });

        return redirect()->route('schooling.students.index')
                         ->with('success', 'Inscription enregistrée avec succès.');
    }

    public function show(Student $student)
    {
        $student->load(['personne.country', 'country', 'documents', 'enrollments']);
        return view('School::schooling.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $student->load('personne');
        $countries = Country::orderBy('name')->get();
        return view('School::schooling.students.edit', compact('student', 'countries'));
    }

    public function update(Request $request, Student $student)
    {
        $personne = $student->personne;

        $validated = $request->validate([
            // Champs Personne
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
            'telephone' => 'nullable|string|max:50',
            'email' => ['nullable', 'email', Rule::unique('personnes', 'email')->ignore($personne->id)],
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Champs Student
            'matricule' => ['required', 'string', Rule::unique('students', 'matricule')->ignore($student->id)],
            'nationality' => 'nullable|exists:countries,id',
            'father_name' => 'nullable|string|max:255',
            'father_job' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:50',
            'mother_name' => 'nullable|string|max:255',
            'mother_job' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:50',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:100',
            'guardian_phone' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($request, $student, $personne, $validated) {
            if ($request->hasFile('photo')) {
                if ($personne->photo && Storage::disk('public')->exists($personne->photo)) {
                    Storage::disk('public')->delete($personne->photo);
                }
                $validated['photo'] = $request->file('photo')->store('students/photos', 'public');
            }

            $personne->update([
                'nom' => $validated['nom'],
                'prenoms' => $validated['prenoms'],
                'sexe' => $validated['sexe'],
                'birth_date' => $validated['birth_date'] ?? null,
                'birth_place' => $validated['birth_place'] ?? null,
                'country_id' => $validated['country_id'] ?? null,
                'telephone' => $validated['telephone'] ?? null,
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'] ?? null,
                'photo' => $validated['photo'] ?? $personne->photo,
            ]);

            $student->update([
                'matricule' => $validated['matricule'],
                'nationality' => $validated['nationality'] ?? null,
                'father_name' => $validated['father_name'] ?? null,
                'father_job' => $validated['father_job'] ?? null,
                'father_phone' => $validated['father_phone'] ?? null,
                'mother_name' => $validated['mother_name'] ?? null,
                'mother_job' => $validated['mother_job'] ?? null,
                'mother_phone' => $validated['mother_phone'] ?? null,
                'guardian_name' => $validated['guardian_name'] ?? null,
                'guardian_relation' => $validated['guardian_relation'] ?? null,
                'guardian_phone' => $validated['guardian_phone'] ?? null,
            ]);
        });

        return redirect()->route('schooling.students.index')->with('success', 'Fiche étudiant mise à jour avec succès.');
    }

    public function generatePdf(Student $student)
    {
        // Chargement des relations pour éviter le problème N+1
        $student->load(['personne.country', 'country', 'documents', 'enrollments']);

        // Traitement de l'image photo en Base64 pour un rendu garanti dans Dompdf
        $photoBase64 = null;
        if (optional($student->personne)->photo) {
            $path = storage_path('app/' . ltrim($student->personne->photo, '/'));
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $photoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        $pdf = Pdf::loadView('School::schooling.students..pdf', compact('student', 'photoBase64'))
                ->setPaper('a4', 'portrait')
                ->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        $fileName = 'Fiche_Etudiant_' . ($student->registration_number ?? $student->id) . '.pdf';

        return $pdf->stream($fileName); // Utilisez ->download($fileName) si vous préférez forcer le téléchargement
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $personne = $student->personne;

            if ($personne && $personne->photo && Storage::disk('public')->exists($personne->photo)) {
                Storage::disk('public')->delete($personne->photo);
            }

            $student->delete();
            if ($personne) {
                $personne->delete();
            }
        });

        return redirect()->route('schooling.students.index')
            ->with('success', 'Étudiant supprimé avec succès.');
    }
}
<?php

namespace App\Modules\School\Http\Controllers\Api\Schooling;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistrationApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Registration::with(['student', 'schoolClass', 'academicYear', 'school']);

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->input('school_id'));
        }

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->input('academic_year_id'));
        }

        if ($request->filled('school_class_id')) {
            $query->where('school_class_id', $request->input('school_class_id'));
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $registrations,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'school_id'        => ['required', 'exists:schools,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'student_id'       => [
                'required', 
                'exists:students,id',
                Rule::unique('registrations')->where(function ($query) use ($request) {
                    return $query->where('school_id', $request->input('school_id'))
                                 ->where('academic_year_id', $request->input('academic_year_id'));
                }),
            ],
            'school_class_id'  => ['required', 'exists:school_classes,id'],
            'type'             => ['required', Rule::in(['new', 're_enrollment'])],
            'status'           => ['required', Rule::in(['pending', 'confirmed', 'canceled', 'transferred'])],
            'registration_fee' => ['nullable', 'numeric', 'min:0'],
            'discount_amount'  => ['nullable', 'numeric', 'min:0'],
            'registration_date'=> ['required', 'date'],
            'notes'            => ['nullable', 'string'],
        ]);

        $validated['registration_number'] = Registration::generateRegistrationNumber();

        $registration = Registration::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Inscription créée avec succès.',
            'data'    => $registration->load(['student', 'schoolClass', 'academicYear']),
        ], 201);
    }

    public function show(Registration $registration): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $registration->load(['student', 'schoolClass', 'academicYear', 'school']),
        ]);
    }

    public function update(Request $request, Registration $registration): JsonResponse
    {
        $validated = $request->validate([
            'school_class_id'  => ['sometimes', 'exists:school_classes,id'],
            'type'             => ['sometimes', Rule::in(['new', 're_enrollment'])],
            'status'           => ['sometimes', Rule::in(['pending', 'confirmed', 'canceled', 'transferred'])],
            'registration_fee' => ['nullable', 'numeric', 'min:0'],
            'discount_amount'  => ['nullable', 'numeric', 'min:0'],
            'registration_date'=> ['sometimes', 'date'],
            'notes'            => ['nullable', 'string'],
        ]);

        $registration->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Inscription mise à jour avec succès.',
            'data'    => $registration->load(['student', 'schoolClass']),
        ]);
    }

    public function destroy(Registration $registration): JsonResponse
    {
        $registration->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inscription supprimée avec succès.',
        ]);
    }
}
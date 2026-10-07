<?php

namespace App\Modules\School\Http\Controllers\Api\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SchoolApiController extends Controller
{
    /**
     * Liste tous les établissements (avec filtres optionnels).
     */
    public function index(Request $request): JsonResponse
    {
        $query = School::with(['currentAcademicYear']);

        // Filtrer par statut d'activité si renseigné
        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        // Filtrer par statut juridique (private, public, confessional)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Recherche par nom ou code
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $schools = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data'    => $schools,
        ]);
    }

    /**
     * Enregistre un nouvel établissement.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tenant_id'                => ['nullable', 'exists:tenants,id'],
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
        ]);

        // Traitement du Logo
        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('schools/logos', 'public');
        }

        // Traitement du Cachet officiel
        if ($request->hasFile('stamp')) {
            $validated['stamp_path'] = $request->file('stamp')->store('schools/stamps', 'public');
        }

        $school = School::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Établissement créé avec succès.',
            'data'    => $school->load('currentAcademicYear'),
        ], 201);
    }

    /**
     * Affiche les détails d'un établissement spécifique.
     */
    public function show(School $school): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $school->load(['currentAcademicYear', 'academicYears']),
        ]);
    }

    /**
     * Met à jour un établissement existant.
     */
    public function update(Request $request, School $school): JsonResponse
    {
        $validated = $request->validate([
            'tenant_id'                => ['nullable', 'exists:tenants,id'],
            'current_academic_year_id' => ['nullable', 'exists:academic_years,id'],
            'name'                     => ['sometimes', 'string', 'max:255'],
            'code'                     => ['sometimes', 'string', 'max:50', Rule::unique('schools', 'code')->ignore($school->id)],
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
            'status'                   => ['sometimes', Rule::in(['private', 'public', 'confessional'])],
            'website'                  => ['nullable', 'url', 'max:255'],
            'document_header'          => ['nullable', 'string', 'max:255'],
            'document_footer'          => ['nullable', 'string'],
            'is_active'                => ['sometimes', 'boolean'],
        ]);

        // Mise à jour du Logo (avec suppression de l'ancien)
        if ($request->hasFile('logo')) {
            if ($school->logo_path && Storage::disk('public')->exists($school->logo_path)) {
                Storage::disk('public')->delete($school->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('schools/logos', 'public');
        }

        // Mise à jour du Cachet (avec suppression de l'ancien)
        if ($request->hasFile('stamp')) {
            if ($school->stamp_path && Storage::disk('public')->exists($school->stamp_path)) {
                Storage::disk('public')->delete($school->stamp_path);
            }
            $validated['stamp_path'] = $request->file('stamp')->store('schools/stamps', 'public');
        }

        $school->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Établissement mis à jour avec succès.',
            'data'    => $school->load('currentAcademicYear'),
        ]);
    }

    /**
     * Définit ou change l'année académique courante (contexte actif).
     */
    public function setCurrentAcademicYear(Request $request, School $school): JsonResponse
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
        ]);

        $school->update([
            'current_academic_year_id' => $validated['academic_year_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Année académique courante modifiée avec succès.',
            'data'    => [
                'school_id'                => $school->id,
                'current_academic_year_id' => $school->current_academic_year_id,
            ],
        ]);
    }

    /**
     * Bascule le statut actif / inactif de l'établissement.
     */
    public function toggleActive(School $school): JsonResponse
    {
        $school->update([
            'is_active' => !$school->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Statut de l\'établissement mis à jour.',
            'data'    => [
                'id'        => $school->id,
                'is_active' => $school->is_active,
            ],
        ]);
    }

    /**
     * Supprime un établissement (Soft Delete).
     */
    public function destroy(School $school): JsonResponse
    {
        $school->delete();

        return response()->json([
            'success' => true,
            'message' => 'Établissement supprimé avec succès.',
        ]);
    }
}
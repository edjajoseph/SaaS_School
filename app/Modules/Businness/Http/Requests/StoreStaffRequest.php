<?php

namespace App\Modules\School\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nom' => $this->nom ? Str::of($this->nom)
                        ->replaceMatches('/[^\p{L}\s\-]/u', '') // 1. Garde les lettres Unicode, espaces et tirets
                        ->ascii()                               // 2. Supprime les accents (É -> E)
                        ->squish()                              // 3. Réduit les espaces multiples
                        ->upper()                               // 4. Passe en majuscules
                        ->toString() : null,

            'prenoms' => $this->prenoms ? Str::of($this->prenoms)
                            ->replaceMatches('/[^\p{L}\s\-]/u', '') // 1. Garde les lettres Unicode, espaces et tirets
                            ->ascii()                               // 2. Supprime les accents (É -> E)
                            ->squish()                              // 3. Réduit les espaces multiples
                            ->upper()                               // 4. Passe en majuscules
                            ->toString() : null,
        ]);
    }

    public function rules(): array
    {
        return [
            // Identité & État Civil (Personne)
            'nom'         => ['required', 'string', 'max:255'],
            'prenoms'     => ['required', 'string', 'max:255'],
            'sexe'        => ['required', Rule::in(['M', 'F'])],
            'civility'    => ['required', Rule::in(['M', 'Mme', 'Mlle'])],
            'sit_mat'     => ['nullable', 'string', 'max:30'],
            'birth_date'  => ['nullable', 'date', 'before:today'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'country_id'  => ['nullable', 'exists:countries,id'],
            'telephone'   => ['nullable', 'string', 'max:20'],
            'email'       => ['nullable', 'email', 'max:255', 'unique:personnes,email'],

            // Staff (Établissement & Références)
            'school_id'     => ['nullable', 'exists:schools,id'],
            'staff_code'    => ['nullable', 'string', 'max:50', 'unique:staff,staff_code'],
            'speciality_id' => ['nullable', 'exists:specialities,id'],
            'degree_id'     => ['nullable', 'exists:degrees,id'],
            'is_active'     => ['sometimes', 'boolean'],

            // Contrat
            'contract_school_id'  => ['required', 'exists:schools,id'],
            'staff_role_id'       => ['required', 'exists:staff_roles,id'],
            'job_title'           => ['required', 'string', 'max:255'],
            'contract_type'       => ['required', Rule::in(['CDI', 'CDD', 'VACATAIRE', 'PRESTATAIRE', 'STAGE'])],
            'start_date'          => ['required', 'date'],
            'end_date'            => ['nullable', 'date', 'after_or_equal:start_date'],
            'pay_type'            => ['required', Rule::in(['monthly', 'hourly', 'forfait'])],
            'base_salary_or_rate' => ['required', 'numeric', 'min:0'],
            'contract_document'   => ['nullable', 'file', 'mimes:pdf,png,jpg', 'max:4096'],
        ];
    }
}
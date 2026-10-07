<?php

namespace app\Modules\School\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nom' => str($this->nom ?? '')
                        ->ascii()
                        ->replaceMatches('/[^\p{L}\s\-]/u', '')
                        ->upper()
                        ->trim()
                        ->toString(),

            'prenoms' => str($this->prenoms ?? '')
                            ->ascii()
                            ->replaceMatches('/[^\p{L}\s\-]/u', '')
                            ->upper()
                            ->trim()
                            ->toString(),
        ]);
    }

    public function rules(): array
    {        
        return [
            // Identité & État Civil (Personne)
            'nom' => ['required', 'string', 'max:255'],
            'prenoms' => ['required', 'string', 'max:255'],
            'sexe'        => ['required', Rule::in(['M', 'F'])],
            'civility'    => ['required', Rule::in(['M', 'Mme', 'Mlle'])],
            'sit_mat'     => ['nullable', 'string', 'max:30'],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],

            // Staff (Établissement & Références)
            'staff_code' => ['required', 'string', 'max:100'],
            'speciality_id' => ['nullable', 'exists:specialities,id'],
            'degree_id' => ['nullable', 'exists:degrees,id'],

            // Contrat
            'contract_school_id' => ['required_if:has_contract,1', 'exists:schools,id'],
            'staff_role_id' => ['required_if:has_contract,1', 'exists:staff_roles,id'],
            'job_title' => ['required_if:has_contract,1', 'string', 'max:255'],
            'contract_type'       => ['required', Rule::in(['CDI', 'CDD', 'VACATAIRE', 'PRESTATAIRE', 'STAGE'])],
            'pay_type'            => ['required', Rule::in(['monthly', 'hourly', 'forfait'])],
            'base_salary_or_rate' => ['required_if:has_contract,1', 'numeric'],
            'start_date' => ['required_if:has_contract,1', 'date'],
            'contract_document' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:5120'],
        ];
    }
}
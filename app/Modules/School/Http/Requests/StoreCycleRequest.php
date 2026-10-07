<?php

namespace App\Modules\School\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCycleRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prépare les données pour la validation (ex: gestion de la checkbox is_active).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }

    /**
     * Règles de validation appliquées à la requête.
     */
    public function rules(): array
    {
        // Récupère le cycle en cours de modification s'il existe dans la route
        $cycleId = $this->route('cycle') ? $this->route('cycle')->id : null;

        return [
            'code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('cycles', 'code')->ignore($cycleId),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'sequence_order' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'is_active' => [
                'boolean',
            ],
        ];
    }

    /**
     * Messages d'erreur personnalisés (en français).
     */
    public function messages(): array
    {
        return [
            'code.required'     => 'Le code du cycle est obligatoire.',
            'code.unique'       => 'Ce code de cycle existe déjà.',
            'code.max'          => 'Le code ne doit pas dépasser 30 caractères.',
            'name.required'     => 'Le nom du cycle est obligatoire.',
            'name.max'          => 'Le nom ne doit pas dépasser 255 caractères.',
            'sequence_order.min' => 'L\'ordre de séquence doit être supérieur ou égal à 1.',
        ];
    }
}
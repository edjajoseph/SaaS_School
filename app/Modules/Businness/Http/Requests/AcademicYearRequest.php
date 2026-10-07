<?php

namespace App\Modules\School\Http\Requests;

//namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;

class AcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->academic_year ? $this->academic_year->id : null;

        return [
            'name'        => 'required|string|max:255|unique:academic_years,name,' . $id,
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after:start_date',
            'is_current'  => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Le nom de l\'année académique est obligatoire.',
            'name.unique'         => 'Cette année académique existe déjà.',
            'end_date.after'      => 'La date de fin doit être postérieure à la date de début.',
        ];
    }
}
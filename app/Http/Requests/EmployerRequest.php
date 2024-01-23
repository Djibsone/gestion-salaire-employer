<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules tha t apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'departement_id' => ['required','integer', 'exists:departements,id'],
            'lastname' => ['required', 'string'],
            'firstname' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('employers', 'email')->ignore($this->route('employer'))],
            'contact' => ['required'],
            'montant_journalier' => ['required']
        ];
    }

    public function messages ()
    {
        return [
            'departement_id.required' => 'Le departement est requis',
            'lastname.required' => 'Le nom est requis.',
            'firstname.required' => 'Le prénom est requis.',
            'email.required' => 'Le email est requis.',
            'email.unique' => 'Le email existe déjà.',
            'contact.required' => 'Le contact est requis.',
            'montant_journalier.required' => 'Le montant à journalier est requis.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'exists:reset_code_passwords,code'],
            'password' => ['required', 'same:confirm_password'],
            'confirm_password' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Le code est requis.',
            'code.exists' => 'Le code n\est pas valide, veuillez consultez votre boite email.',
            'password.same' => 'Les mots de passe sont pas identiques.',
            'confirm_password.same' => 'Les mots de passe sont pas identiques.'
        ];
    }
}

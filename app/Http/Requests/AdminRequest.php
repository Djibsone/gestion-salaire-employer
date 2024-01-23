<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
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
            'name' => ['required'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->route('admin'))],
            'password' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom de l\'administrateur est requis.',
            'email.required' => 'Le mail est requis.',
            'email.unique' => 'Le mail existe déjà.',
            // 'password.required' => 'Le mot de passe est requis.'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'password' => Hash::make('password')
        ]);
    }
}

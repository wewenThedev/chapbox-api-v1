<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
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
        //dd($_REQUEST);
        return [
            //
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            //'username' => 'string|unique:users|min:4|max:255',
            //'phone' => 'required|string|min:8|max:15|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            //'password' => 'required|string|min:8|confirmed',
            'profile_id' => 'required|exists:profiles,id',

        ];
    }

    // Messages personnalisés (optionnel)
    public function messages()
    {
        return [
            'email.required' => 'L\'email est obligatoire',
            'password.min' => 'Le mot de passe doit faire au moins 8 caractères',
        ];
    }

    // Pour les APIs : Retourner une réponse JSON au lieu d'une redirection
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}

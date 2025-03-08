<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //appel aux middleware après
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
            'username' => 'string|unique:users|min:4|max:255',
            'phone' => 'string|max:15',
            'email' => 'string|email|max:255|unique:users',
            'profile_id' => 'required|exists:profiles,id',
            'password' => 'required|string|min:8|confirmed',
            
        ];
    }
}

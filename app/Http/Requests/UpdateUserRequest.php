<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        
        //dd($_SERVER);
        return [
            'firstname' => 'string|max:255',
            'lastname' => 'string|max:255',
            'username' => 'string|unique:users|min:4|max:255',
            'phone' => 'string|max:15',
            'email' => 'string|email|max:255|unique:users,email',
            'profile_id' => 'int|exists:profiles,id',
            //à modifier
            'picture_id' => 'int|exists:media,id',
            'password' => 'string|min:8|confirmed',
/*
            'firstname' => 'sometimes|required|string|max:255',
            'lastname' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|unique:users|min:4|max:255',
            'phone' => 'sometimes|required|string|max:15',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $this->user->id,
            'profile_id' => 'sometimes|required|int|exists:profiles,id|,' . $this->user->profile_id,
            'picture_id' => 'sometimes|required|int|exists:media,id|,' . $this->user->picture_id,
*/
        ];
    }
}

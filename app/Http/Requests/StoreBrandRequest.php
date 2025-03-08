<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //return false;
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
            //
            'name' => 'required|string|max:255',
            'description' => 'string|max:500',
            //'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_id' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            //'url' => 'required|url',

        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //condition sur l'accès - role admin uniquement
        //if(auth()->check() && auth()->user->profile === 1){
        return true;
        /*}else{
            return false;
        }*/
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            //'logo_id' => 'required|exists,media:id',
            //'logo' => 'required|file',
            'logo_id' => 'image|mimes:jpeg,png,jpg|max:2048',
            'terms_conditions' => 'required|string',
            'fees' => 'required|numeric'
        ];
    }
}

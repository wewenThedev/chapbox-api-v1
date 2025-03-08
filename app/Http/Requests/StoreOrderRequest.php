<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
        if(1){
        //dd($_REQUEST);

            return [
                //
                //'shopping_details_id' => 'required|array|exists:shopping_details,id',
                //vérification de l'utilisateur connecté
                'cart_id' => 'required|exists:carts,id',
                'user_id' => 'sometimes|exists:users,id',
                'guest_firstname' => 'sometimes|string|max:255',
                'guest_lastname' => 'sometimes|string|max:255',
                'guest_phone' => 'sometimes|string|max:15|unique:users',
                'guest_email' => 'nullable|email|max:255|unique:users',
                'total_ht' => 'numeric',
                /*'shipping_date' => 'required|date',
                'shipping_address' => 'required|date',*/
                'recovery_mode'     => 'required|in:pickup,delivery',
                'payment_method_id' => 'required|exists:payment_methods,id',
                'shipping_date' => 'required|string|max:255',
                'shipping_address' => 'required|string|max:255',
            ];
        }else{
            return [
                'status' => 'error',
                'errors' => 'message'
            ];
        }
    }
}

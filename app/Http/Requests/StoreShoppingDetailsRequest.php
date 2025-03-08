<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShoppingDetailsRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'shop_id' => 'required|exists:shops,id',
            'quantity' => 'required|integer|min:1',
            'cart_id' => 'exists:carts,id',
            'device_id' => 'string',
            'force_update' => 'integer',        
        ];
    }
}

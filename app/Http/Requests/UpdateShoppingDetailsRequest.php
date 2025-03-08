<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShoppingDetailsRequest extends FormRequest
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
            /*'product_id' => 'required|exists:products,id',
            'shop_id' => 'required|exists:shops,id',
            'cart_id' => 'required|exists:carts,id',*/
            'shopping_detail_id' => 'required|exists:shopping_details,id',
            'quantity' => 'required|integer|min:1',
            'increase' => 'integer',
        ];
    }
}

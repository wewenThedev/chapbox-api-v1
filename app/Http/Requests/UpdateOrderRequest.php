<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            /*
            'guest_firstname',
            'guest_lastname',
            'guest_phone',
            'guest_email',*/
            //'order_id' => 'required|exists:orders,id',
            //correct this validation rule
            //'status' => 'required|string|in:pending,processing,failed,successful'
            'status' => 'required|string'
        ];
    }
}

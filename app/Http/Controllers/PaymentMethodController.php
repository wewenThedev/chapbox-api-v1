<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;


use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;

class PaymentMethodController extends Controller
{

    public function generatePaymentMethodMethodLogoFilename($paymenMethodMethod) {
        $timestamp = time();
        $extension = $this->getFileExtension($paymenMethodMethod->logo); // Ex : .png, .jpg
        return 'PaymentMethod_method_logo_' . $paymenMethodMethod->id . '_' . $timestamp . $extension;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //return PaymentMethod::all();
        return PaymentMethod::paginate(2);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentMethodRequest $request)
    {
        //
        $paymenMethod = PaymentMethod::create($request->validated());
        return response()->json($paymenMethod, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id /*PaymentMethod $paymenMethod*/)
    {
        $paymenMethod = PaymentMethod::findOrFail($id);
        return $paymenMethod;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, string $id /*PaymentMethod $paymenMethod*/)
    {
        $paymenMethod = PaymentMethod::findOrFail($id);
        $paymenMethod->update($request->validated());
        return response()->json($paymenMethod);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id /*PaymentMethod $paymenMethod*/)
    {
        $paymenMethod = PaymentMethod::findOrFail($id);
        $paymenMethod->delete();
        return response()->json(null, 204);

    }
}

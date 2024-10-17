<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{

    public function generatePaymentMethodLogoFilename($paymentMethod) {
        $timestamp = time();
        $extension = $this->getFileExtension($paymentMethod->logo); // Ex : .png, .jpg
        return 'payment_method_logo_' . $paymentMethod->id . '_' . $timestamp . $extension;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

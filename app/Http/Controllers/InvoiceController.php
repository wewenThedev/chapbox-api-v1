<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;


use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //return Invoice::all();
        return Invoice::paginate(2);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        //
        $invoice = Invoice::create($request->validated());
        return response()->json($invoice, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id /*Invoice $invoice*/)
    {
        $invoice = Invoice::findOrFail($id);
        return $invoice;
    }

    /**
     * Update the specified resource in storage.
     */
    /*public function update(UpdateInvoiceRequest $request, string $id /*Invoice $invoice)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->validated());
        return response()->json($invoice);

    }*/

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id /*Invoice $invoice*/)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return response()->json(null, 204);

    }
}

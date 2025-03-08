<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UpdatePromoRequest;
use App\Http\Requests\StorePromoRequest;

use App\Models\Promo;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //return Promo::all();
        return Promo::paginate(2);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePromoRequest $request)
    {
        //
        $promo = Promo::create($request->validated());
        return response()->json($promo, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id /*Promo $promo*/)
    {
        $promo = Promo::findOrFail($id);
        return $promo;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePromoRequest $request, string $id /*Promo $promo*/)
    {
        $promo = Promo::findOrFail($id);
        $promo->update($request->validated());
        return response()->json($promo);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id /*Promo $promo*/)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();
        return response()->json(null, 204);

    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\User;

class CartController extends Controller
{
    /**
     * Initialize Cart when user create account or open the application for the first time
     */
    public function createCart(StoreCartRequest $request)
{

    $userId = $request->user()->id ?? null;
    $deviceId = $request->header('device-id'); // Assumes device-id is sent as a header for unauthenticated users

    // Crée un nouveau panier
    $cart = Cart::create([
        'user_id' => $userId,
        'device_id' => $deviceId,
    ]);

    return response()->json(['cart' => $cart], 201);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\User;

class CartController extends Controller
{
    Public function createCart(Request $request)
{

    $userId = $request->user()->id ?? null;
    $deviceId = $request->header('device-id'); // Assumes device-id is sent as a header for unauthenticated users

    // Crée un nouveau panier
    $cart = Cart::create([
        'user_id' => $userId,
        'device_id' => $deviceId,
    ]);

    Return response()->json(['cart' => $cart], 201);
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

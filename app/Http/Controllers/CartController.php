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
        //return Cart::all();
        return Cart::paginate(2);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        //
        $cart = Cart::create($request->validated());
        return response()->json($cart, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id /*Cart $cart*/)
    {
        $cart = Cart::findOrFail($id);
        return $cart;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, string $id /*Cart $cart*/)
    {
        $cart = Cart::findOrFail($id);
        $cart->update($request->validated());
        return response()->json($cart);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id /*Cart $cart*/)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return response()->json(null, 204);

    }

}

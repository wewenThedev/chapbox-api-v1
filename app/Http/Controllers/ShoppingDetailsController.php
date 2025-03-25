<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShoppingDetailsRequest;
use App\Http\Requests\UpdateShoppingDetailsRequest;
use Illuminate\Http\Request;

/*
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
*/

use App\Models\Cart;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShoppingDetails;
use App\Models\ShopProduct;

use Illuminate\Support\Facades\DB;

class ShoppingDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //$shoppingDetails = ShoppingDetails::withTrashed()->get();

        $shoppingDetails = ShoppingDetails::all();

        dd($shoppingDetails->count());

        // $shoppingDetails = ShoppingDetails::paginate(5);
    
        // $shoppingDetails = ShoppingDetails::all();
        return response()->json($shoppingDetails, 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShoppingDetailsRequest $request)
    {
        //
        $shoppingDetails = ShoppingDetails::create($request->validated());
        return response()->json($shoppingDetails, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //$shoppingDetails = ShoppingDetails::findOrFail($id);
        $shoppingDetails = ShoppingDetails::with(['cart', 'product', 'shop', 'order'])->findOrFail($id);
        
        return response()->json($shoppingDetails, 200);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShoppingDetailsRequest $request, $id)
    {
        $shoppingDetails = ShoppingDetails::findOrFail($id);
        $shoppingDetails->update($request->validated());
        return response()->json($shoppingDetails);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shoppingDetails = ShoppingDetails::findOrFail($id);
        $shoppingDetails->delete();
        return response()->json(null, 204);

    }

    public function addItemToCart(UpdateShoppingDetailsRequest $request){
        $requestValidated = $request->validated();

        $cart = Cart::find($requestValidated['cart_id']);
    if (!$cart) {
        return response()->json(['error' => 'Cart not found.'], 404);
    }

    $product = Product::find($requestValidated['product_id']);
    if (!$product) {
        return response()->json(['error' => 'Product not found.'], 404);
    }

    // Vérifier si le produit appartient à la même boutique que ceux dans le panier
    $shopId = $product->shop_id;
    $items = $cart->items()->pluck('shop_id')->unique();
    if ($items->isNotEmpty() && !$items->contains($shopId)) {
        return response()->json(['error' => 'Cannot add products from different shops to the same cart.'], 400);
    }

    // Ajouter le produit au panier
    $cartItem = $cart->items()->updateOrCreate([
        'product_id' => $product->id,
        'quantity' => $requestValidated['quantity'],
        'shop_id' => $shopId
    ]);

    return response()->json(['cart_item' => $cartItem], 200);

    }

    public function updateItemQuantity(UpdateShoppingDetailsRequest $request){
        $requestValidated = $request->validated();

        //pas terminé
    }

    Public function removeItemFromCart(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'cart_id' => 'required|exists:carts,id',
    ]);

    $cart = Cart::find($validated['cart_id']);
    If (!$cart) {
        Return response()->json(['error' => 'Cart not found.'], 404);
    }

    // Supprimer le produit du panier
    $cart->shoppingDetails()->where('product_id', $validated['product_id'])->delete();

    // Recalculer le total après suppression
    $total = $cart->shoppingDetails()->sum(DB::raw('quantity * unit_price'));

    Return response()->json([
        'message' => 'Item removed from cart.',
        'total' => $total
    ], 200);
}


    Public function viewCart(Request $request)
{
    $cartId = $request->query('cart_id');
    $cart = Cart::with('shoppingDetails.product')->find($cartId);
    If (!$cart) {
        Return response()->json(['error' => 'Cart not found.'], 404);
    }

    // Calculer le total
    $total = $cart->shoppingDetails()->sum(DB::raw('quantity * unit_price'));

    Return response()->json([
        'cart' => $cart,
        'total' => $total
    ], 200);
}


public function getShoppingCartTotal(string $id){

    $shoppingCartTotal = 0;

    return response()->json($shoppingCartTotal, 200);
}

}
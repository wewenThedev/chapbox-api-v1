<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShoppingDetailsRequest;
use App\Http\Requests\UpdateShoppingDetailsRequest;
use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShoppingDetails;
use App\Models\ShopProduct;

class ShoppingDetailsController extends Controller
{
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
    public function store(StoreShoppingDetailsRequest $request)
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
    public function update(UpdateShoppingDetailsRequest $request, string $id)
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
    }

    public function removeItemFromCart(Request $request){
        $requestValidated = $request->validated();

        $cart = Cart::find($requestValidated['cart_id']);
    if (!$cart) {
        return response()->json(['error' => 'Cart not found.'], 404);
    }

    // Supprimer le produit du panier
    $cart->items()->where('product_id', $requestValidated['product_id'])->delete();

    return response()->json(['message' => 'Item removed from cart.'], 200);


    }

    public function viewCart(Request $request)
{
    $cartId = $request->query('cart_id');
    $cart = Cart::with('items.product')->find($cartId);

    if (!$cart) {
        return response()->json(['error' => 'Cart not found.'], 404);
    }

}
}
<?php

namespace App\Models;

namespace App\Services;

use App\Models\Cart;
use App\Models\ShoppingDetails;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\ShopProduct;
use FedaPay\Transaction;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\CartController;

class CartService
{

    /**
     * Récupère le panier de l'utilisateur ou celui identifié par device_id 
     * pour les utilisateurs non authentifiés.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Cart|null
     */
    private function getUserCart(/*Request $request*/$device_id, ?string $user_id)
    {
        // Si l'utilisateur est authentifié, on utilise son user_id.
        if (auth()->check()) {
            return Cart::firstOrCreate(
                ['user_id' => auth()->id()],
                ['device_id' => $device_id ?? null]
            );
        } elseif ($device_id !== null) {
            // Pour un utilisateur non authentifié, on utilise le device_id pour retrouver (ou créer) le panier.
            return Cart::firstOrCreate(
                ['device_id' => $device_id],
                ['user_id' => null]
            );
        }

        return null;
    }

    // Méthode pour vérifier si un produit est dans le panier
    /*public function hasProduct($productId)
    {
        return $this->shoppingDetails->contains('id', $productId);
    }*/

    public function getProductsInCart()
    { //to edit
        /*dd($result);
        return $result;*/
    }

    // Méthode pour calculer le total du panier
    public function getTotalPrice($cartId)
    {
        $total = ShoppingDetails::where('cart_id', $cartId)->sum('cost');
        //return response()->json(['total' => $total], 200);
        return $total;
    }

    /**
     * Ajoute un produit au panier.
     *
     * Exigences attendues dans la requête :
     * - product_id : ID du produit à ajouter (doit exister dans la table products)
     * - shop_id    : ID de la boutique (filiale) à laquelle appartient le produit
     * - quantity   : Quantité souhaitée (>= 1)
     * - force_update (optionnel) : booléen indiquant si l’utilisateur souhaite augmenter la quantité
     *                              si le produit est déjà dans le panier.
     * - device_id  (optionnel) : identifiant du dispositif, utile lorsque l’utilisateur n’est pas authentifié.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    //public function addToCart(?string $userId, $deviceId, $productId, $shopId, int $quantity)
    public function addToCart($request)
    {
        //Identifier l'utilisateur
        /*dd($request->user_id);
        $user = User::with('cart')->where('id', $request->user_id)->first();
        */

        // Trouver le panier pour l'utilisateur
        //$cart = $this->getUserCart($request->device_id, $request->user_id);

        $cart = Cart::find($request->cart_id);

        //dd($cart);
        if (!$cart) {
            // Si aucun panier n'est trouvé, on le crée.
            $cart = Cart::create([
                'user_id'   => auth()->check() ? auth()->id() : null,
                'device_id' => $request->device_id ?? null,
            ]);
        }

//dd($cart);
$user = User::with('cart')->where('id', $cart->user_id)->first();
//dd($user);
        DB::beginTransaction();

        try{

        $existingDetails = ShoppingDetails::with(['cart', 'shop', 'product'])->where('cart_id', $cart->id)->get();
        //dd($existingDetails);
        if ($existingDetails->count() > 0) {
            // Puisque tous les articles doivent provenir de la même boutique, on compare avec le premier enregistrement.
            $firstItem = $existingDetails->first();
            if ($firstItem->shop_id != $request->shop_id) {
                return response()->json([
                    'error' => 'Vous ne pouvez pas ajouter des produits provenant de boutiques différentes dans le même panier.',
                ], 400);
            }
        }

        /*
        // 4. S'il existe déjà des articles dans le panier, on vérifie que tous les items proviennent de la même boutique.
$existingDetails = ShoppingDetails::where('cart_id', $cart->id)->get();
foreach ($existingDetails as $item) {
    if ($item->shop_id != $request->shop_id) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Vous ne pouvez pas ajouter des produits provenant de boutiques différentes dans le même panier.',
        ], 400);
    }
}*/

        // contrainte stock>1 /-- Vérifier que le produit est disponible dans la boutique spécifiée 

        //$shopProduct = ShopProduct::with(['product', 'shop'])->where('shop_id', $request->shop_id)->where('product_id', $request->product_id)->where('stock', '>', '1')->first();
        $shopProduct = ShopProduct::with(['product', 'shop'])->where('shop_id', $request->shop_id)->where('product_id', $request->product_id)->first();
        //dd($shopProduct);
        if (!$shopProduct) {
    return response()->json([
        'error' => 'Ce produit n\'est pas disponible dans la boutique sélectionnée.',
    ], 404);
}

        // Vérifier la disponibilité du stock pour la quantité demandée
        if ($shopProduct->stock < $request->quantity) {
            return response()->json([
                'error' => 'Stock insuffisant pour ce produit.',
            ], 400);
        }

        // 5. Vérifier si le produit est déjà présent dans le panier
        $existingItem = ShoppingDetails::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->where('shop_id', $request->shop_id)
            ->withoutTrashed()
            ->first();

        if ($existingItem) {
            // Le produit est déjà dans le panier.
            // On vérifie si l’utilisateur a indiqué vouloir mettre à jour la quantité (via le paramètre force_update).
            if (!$request->has('force_update') || !$request->force_update) {
                return response()->json([
                    'status'  => 'exists',
                    'message' => 'Ce produit est déjà dans le panier. Pour augmenter la quantité, définissez le paramètre force_update à true.',
                ], 200);
            }

            $newQuantity = $existingItem->quantity + $request->quantity;
            if ($shopProduct->stock < $newQuantity) {
                return response()->json([
                    'error' => 'Le stock est insuffisant pour la quantité mise à jour.',
                ], 400);
            }

            $existingItem->quantity = $newQuantity;
            $existingItem->save();

            return response()->json([
                'success' => 'La quantité du produit dans le panier a été mise à jour.',
                'data'    => $existingItem,
            ], 200);
        }

        // Si le produit n'était pas déjà dans le panier, on l’ajoute
        $shoppingDetail = ShoppingDetails::create([
            'cart_id'    => $cart->id,
            'shop_id'    => $request->shop_id,
            'product_id' => $request->product_id,
            'quantity'   => $request->quantity,
            'cost'       => $shopProduct->price * $request->quantity, // On récupère le prix depuis shop_products
            'added_at'   => now(),
        ]);

        $shoppingDetail = $shoppingDetail->fresh();
        if ($shoppingDetail->exists) {
            $shoppingDetail->load(['shop', 'product', 'cart', 'order']);
        }
        //$shoppingDetail->load(['shop', 'product', 'cart', 'order']);
//dd($shoppingDetail);
        return response()->json([
            'success' => 'Produit ajouté au panier avec succès.',
            'data'    => $shoppingDetail,
        ], 201);
    }catch(\Exception $e){
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 404);
    }
    }

    /**
     * Augmente la quantité d'un produit dans le panier.
     *
     * Requête attendue :
     * - shopping_detail_id : ID de l'enregistrement dans shopping_details
     *
     * Vérifie que l'augmentation ne dépasse pas le stock disponible.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function increaseQuantity($request)
    {

        $shoppingDetail = ShoppingDetails::find($request->shopping_detail_id);

        //dd($shoppingDetail);
        $cart = $this->getUserCart($shoppingDetail->cart->device_id, $shoppingDetail->cart->user_id);

        if (!$cart || $shoppingDetail->cart_id !== $cart->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Panier non trouvé ou accès non autorisé.',
            ], 403);
        }

        // Vérification du stock disponible pour le produit dans la boutique
        $shopProduct = ShopProduct::where('shop_id', $shoppingDetail->shop_id)
                        ->where('product_id', $shoppingDetail->product_id)
                        ->first();
        //dd($shopProduct);

        if (!$shopProduct) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Le produit n\'est pas disponible dans la boutique.',
            ], 404);
        }

        //$newQuantity = $shoppingDetail->quantity + 1;
        $newQuantity = $shoppingDetail->quantity + $request->quantity;
        //dd($newQuantity);
        if ($newQuantity > $shopProduct->stock) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Stock insuffisant pour augmenter la quantité.',
            ], 400);
        }
        DB::beginTransaction();

        try{
        $shoppingDetail->quantity = $newQuantity;
        $shoppingDetail->cost = $shoppingDetail->calculateCost();
        $shoppingDetail->save();
        //dd($shoppingDetail->save());

        $shoppingDetail->load(['shop','product', 'cart', 'order']);

        // Calcul du coût total du panier
        $shoppingDetails = ShoppingDetails::where('cart_id', $cart->id)->get();
        $totalCost = $shoppingDetails->sum(DB::raw('cost'));
        //dd($totalCost);

        DB::commit();

        return response()->json([
            'status'  => 'success',
            'message' => 'Quantité augmentée avec succès.',
            'data'    => [
                'shopping_detail' => $shoppingDetail,
                'total_cost'      => $totalCost,
            ],
        ], 200);
    }catch(\Exception $e){
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 404);
    }
    }

    /**
     * Diminue la quantité d'un produit dans le panier.
     *
     * Requête attendue :
     * - shopping_detail_id : ID de l'enregistrement dans shopping_details
     *
     * Si la quantité atteint 1, l'item est supprimé du panier.
     * vérifier quand ça va arriver à 0
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function decreaseQuantity($request)
    {
        /*$validator = Validator::make($request->all(), [
            'shopping_detail_id' => 'required|exists:shopping_details,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }*/

        //$shoppingDetail = ShoppingDetails::where('cart_id', $request->cart_id)->get();
        $shoppingDetail = ShoppingDetails::find($request->shopping_detail_id);

        //dd($shoppingDetail);
        $cart = $this->getUserCart($shoppingDetail->cart->device_id, $shoppingDetail->cart->user_id);
        
        if (!$cart || $shoppingDetail->cart_id !== $cart->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Panier non trouvé ou accès non autorisé.',
            ], 403);
        }

        DB::beginTransaction();

        try{
            // Si la quantité est déjà à 1, on retire l'item du panier
        if ($shoppingDetail->quantity <= 1) {
            $shoppingDetail->quantity = $shoppingDetail->quantity - 1;
            $shoppingDetail->delete();
            $message = 'Produit retiré du panier.';
        } else {
            //$shoppingDetail->quantity = $shoppingDetail->quantity - 1;
            $shoppingDetail->quantity = $shoppingDetail->quantity - $request->quantity;
            $shoppingDetail->cost = $shoppingDetail->calculateCost();
            $shoppingDetail->save();
            $message = 'Quantité diminuée avec succès.';
        }

        $shoppingDetail->load(['shop','product', 'cart', 'order']);


        // Calcul du coût total du panier après mise à jour
        $shoppingDetails = ShoppingDetails::where('cart_id', $cart->id)->get();
        $totalCost = $shoppingDetails->sum(DB::raw('cost'));
        //dd($totalCost);

        /*$productsInCart = ShoppingDetails::where('cart_id', $cart->id);
        $totalCost = $productsInCart->getCartTotal($cart->id);*/

        DB::commit();

        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => [
                'shopping_details' => $shoppingDetails,
                'total_cost'      => $totalCost,
            ],
        ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 404);
        }
        
    }


    /**
     * Supprime un produit du panier.
     *
     * Requête attendue :
     * - shopping_detail_id : ID de l'enregistrement dans shopping_details à supprimer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shopping_detail_id' => 'required|exists:shopping_details,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $shoppingDetail = ShoppingDetails::find($request->shopping_detail_id);
        $cart = $this->getUserCart($request->device_id, $request->user_id);

        if (!$cart || $shoppingDetail->cart_id !== $cart->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Panier non trouvé ou accès non autorisé.',
            ], 403);
        }

        $shoppingDetail->delete();

        // Recalcul du coût total du panier après suppression
        $totalCost = ShoppingDetails::where('cart_id', $cart->id)
            ->sum(DB::raw('quantity * cost'));

        return response()->json([
            'status'  => 'success',
            'message' => 'Produit supprimé du panier avec succès.',
            'data'    => [
                'total_cost' => $totalCost,
            ],
        ], 200);
    }

    ///

    /**
     * Vide le panier de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCart(Request $request)
    {
        $cart = $this->getUserCart($request->device_id, $request->user_id);

        if (!$cart) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Panier non trouvé.',
            ], 404);
        }

        // Suppression de tous les éléments associés au panier
        ShoppingDetails::where('cart_id', $cart->id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Panier vidé avec succès.',
            'data'    => [
                'total_cost' => 0,
            ],
        ], 200);
    }

}





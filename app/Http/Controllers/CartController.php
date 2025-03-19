<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Requests\StoreShoppingDetailsRequest;
use App\Http\Requests\UpdateShoppingDetailsRequest;

use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\ShoppingDetails;
use App\Models\ShopProduct;

use App\Services\CartService;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/*
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
*/

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
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

    //fonction pour afficher le panier d'un utilisateur - customer
    public function viewCart()
    {
        echo ('success');
        //to write for admin Maybe
    }

    /**
     * Display a listing of the resource.
     * Liste de tous les paniers existants pour tous les utilisateurs
     */
    public function index()
    {
        //$carts = Cart::paginate(2);
        $carts = Cart::all();
        return response()->json($carts, 200);
        //return response()->json(ShoppingDetails::onlyTrashed()->where('order_id','!=',null)->get(), 200);
    }



    public function addItemToCart(StoreShoppingDetailsRequest $request)
    {
        $user = auth()->user(); // Récupérer l'utilisateur connecté
        //$quantity = $request->input('quantity', 1); // Quantité par défaut : 1

        try {
            return $this->cartService->addToCart($request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Calculer le total des produits contenus dans le panier
     */
    public function getCartTotal($cartId)
    {
        return $this->cartService->getTotalPrice($cartId);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        DB::beginTransaction();

        try {
            $cart = Cart::create($request->validated());

            DB::commit();
            return response()->json($cart, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => $e->getMessage(),
                'status' => 422,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cart = Cart::find($id);
        if($cart){
            return response()->json([
                'cart' => $cart,
                'status' => 200
            ]);
        }else{
            return response()->json(['error' => 'Panier non trouvé'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, $id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            if ($cart->update($request->validated())) {
                return response()->json(
                    [
                        'success' => 'Panier mis à jour avec succès',
                        'cart' => $cart
                    ],
                    20
                );
            } else {
                return response()->json(['error' => 'Echec de la mise à jour du panier'], 400);
            }
            //dd($user);
        } else {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }
    }

    /**
     * Ceci est la fonction pour mettre à jour le panier d'un utilisateur
     * Il peut s'agir soit de l'augmentation de la quantité 
     * ou soit de la diminution de la quantité
     */
    public function updateProductInCart(UpdateShoppingDetailsRequest $request)
    {
        //need the quantity in the request
        //critère increase = 0 ou 1 mais on ne sait jamais donc throw exception
        if ((int)$request->increase === 1) {
            return $this->cartService->increaseQuantity($request);
        } else if ((int) $request->increase === 0) {
            return $this->cartService->decreaseQuantity($request);
        } else {
            return response()->json(['error' => 'Action not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cart = Cart::find($id);

        if ($cart) {
            if ($cart->delete()) {
                return response()->json(
                    [
                        'success' => 'Panier supprimé avec succès',
                        'cart' => $cart
                    ],
                    204
                );
            } else {
                return response()->json(['error' => 'Echec de la suppression du panier'], 404);
            }
        } else {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        //return response()->json(null, 204);
    }

    /// scheduled task to updateSupermarketInDbAddress(){}


}

/**
 * Récupère le panier de l'utilisateur authentifié ou celui basé sur device_id pour les non-authentifiés.
 *
 */
    /*private function getCart(Request $request)
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(
                ['user_id' => auth()->id()],
                ['device_id' => $request->device_id ?? null]
            );
        } elseif ($request->has('device_id')) {
            return Cart::firstOrCreate(
                ['device_id' => $request->device_id],
                ['user_id' => null]
            );
        }
        return null;
    }

    
        /*$total = 0;
        $cartItems = ShoppingDetails::where('cart_id', $cartId)->get();
        //dd($cartItems);
        foreach($cartItems as $cartItem){
            $total += $cartItem->cost;
        }
*/
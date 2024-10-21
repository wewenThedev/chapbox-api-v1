<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

use App\Models\User;
use App\Models\Profile;
use App\Models\Media;
use App\Models\Order;
use App\Models\Address;
use App\Models\Cart;
use App\Models\ShoppingDetails;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
        $user = User::create($request->validated());
        return response()->json($user, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id /*User $user*/)
    {
        $user = User::findOrFail($id);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id /*User $user*/)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return response()->json($user);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id /*User $user*/)
    {
        //
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);

    }

    public function getOrderHistory()
{
    // Assurez-vous que l'utilisateur est authentifié
    $user = auth()->user();

    // Récupérer toutes les commandes de l'utilisateur
    $orders = Order::where('user_id', $user->id)->with('shoppingDetails')->get();

    return response()->json($orders);
}


public function getCartProducts()
{
    // Assurez-vous que l'utilisateur est authentifié
    $user = auth()->user();

    // Récupérer les détails du panier
    $cartDetails = ShoppingDetails::where('cart_id', $user->cart_id)->with('product')->get();

    return response()->json($cartDetails);
}


public function getSavedAddresses()
{
    // Assurez-vous que l'utilisateur est authentifié
    $user = auth()->user();

    // Récupérer les adresses sauvegardées de l'utilisateur
    $addresses = Address::where('user_id', $user->id)->get();

    return response()->json($addresses);
}


public function updateSavedAddress(string $id, Request $request)
{
    // Valider les données de la requête
    $request->validate([
        'name' => 'required|string|max:255',
        'fullAddress' => 'required|string',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    // Trouver l'adresse par ID
    $address = Address::findOrFail($id);
    
    // Mettre à jour les informations de l'adresse
    $address->update($request->all());

    return response()->json($address);
}


public function removeSavedAddress(string $id)
{
    // Trouver l'adresse par ID
    $address = Address::findOrFail($id);
    
    // Supprimer l'adresse
    $address->delete();

    return response()->json(['message' => 'Address deleted successfully.']);
}


public function getProfilePicture()
{
    // Assurez-vous que l'utilisateur est authentifié
    $user = auth()->user();

    // Récupérer l'image de profil de l'utilisateur
    $profilePicture = Media::find($user->picture_id);

    return response()->json($profilePicture);
}


}

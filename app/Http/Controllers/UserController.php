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
     public function getUserOrdersHistory($id)
     {
         // Assurez-vous que l'utilisateur est authentifié
         $user = auth()->user();
         //dd($user);
         // Récupérer toutes les commandes de l'utilisateur
         $orders = Order::where('user_id', $id/*$user->id*/)->with('shoppingDetails')->get();
     
         if($orders){
                 return response()->json(
                     [
                     'orders' => $orders
                     ], 200);
         }else{
             return response()->json(['error' => 'Aucune commande passée'], 404);
         }
     }
     
     
     public function getUserCartProducts($id)
     {
         // Assurez-vous que l'utilisateur est authentifié
         $user = auth()->user();
     
     $userCartId = Cart::where('user_id', $id)->pluck('id')[0];
     //dd($userCart);
         // Récupérer les détails du panier
         $cartDetails = ShoppingDetails::where('cart_id', $userCartId/*$user->cart*/)->with(['product', 'shop'])->get();
     
         return response()->json(['cartDetails' => $cartDetails], 200);
     }
     
     
     public function getSavedAddresses()
     {
         // Assurez-vous que l'utilisateur est authentifié
         $user = auth()->user();
     
         // Récupérer les adresses sauvegardées de l'utilisateur
         $addresses = Address::where('user_id', $user->id)->get();
     
         return response()->json(['addresses' => $addresses]);
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
     
         return response()->json(['address' => $address]);
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
     
         return response()->json(['profile_picture' => $profilePicture], 200);
     }
     
     public function updateProfilePicture($request){
     //to write
     }

     public function getUserTotalOrders($userId){

        $totalOrdersForUser = Order::where('user_id', $userId)->count();
        
        return response()->json(['totalOrdersForUser' => $totalOrdersForUser], 200);
    }
     
    /**
     * Display a listing of the resource.
     */

    public function index(?Request $request)
    {
        $query = User::query();

        //dd(User::with(['profile', 'picture', 'notifications', 'orders'])->get());
        $users = User::with(['profile', 'picture', 'notifications', 'orders'])->get();
        //$users = User::all();
        $totalUsers = $users->count();

        return response()->json(['users' => $users, 'totalUsers' => $totalUsers], 200);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //

        //vérifier si le nom d'utilisateur est déjà occupé , le numéro aussi et l'adresse mail pour savoir ce qu'il se passe
        
        $user = User::create($request->validated());
        return response()->json(['user' => $user], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //$user = User::with(['profile', 'picture'])->findOrFail($id);
        $user = User::with(['profile', 'picture', 'notifications', 'orders'])->find($id);
        //dd($user);
        if($user){
            return response()->json(['user' => $user], 200);
        }else{
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        //dd($request->validated());
        $user = User::with(['profile', 'picture'])->findOrFail($id);
        if($user){
            if($user->update($request->validated())){
                return response()->json(
                    ['success' => 'Informations de l\'utilisateur modifié avec succès',
                    'user' => $user
                    ], 200);
            }else{
                return response()->json(['error' => 'Echec de la mise à jour des informations'], 404);
            }
        //dd($user);
        }else{
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $user = User::with(['profile', 'picture'])->find($id);
        //dd($user);
        if($user){
            if($user->delete()){
                return response()->json(
                    ['success' => 'Compte utilisateur supprimé avec succès',
                    'user' => $user
                    ], 204);
            }else{
                return response()->json(['error' => 'Echec de la suppression du compte utilisateur'], 404);
            }
        //dd($user);
        }else{
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }
    }



}

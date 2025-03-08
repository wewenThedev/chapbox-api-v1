<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function registerCustomer(StoreUserRequest $request)
    {
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'profile_id' => $request->profile_id, //3 -- Customer
        ]);
        //Fonction pour créer le panier au moment de la création du compte à la première ouverture de l'application
        $oldCart = Cart::where('device_id', $request->device_id)->first();
        //dd($cart);
        if ($oldCart !== null) {
            $oldCart->user_id = $user->id;
            $oldCart->save();
        } else {
            $newCart = Cart::create([
                'user_id' => $user->id,
                'device_id' => $request->device_id,
            ]);
            //dd($newCart);
        }

        return response()->json($user, 201);
    }

    public function registerAdminOrManager(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_id' => 'required|in:1,2', // Admin ou Manager
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_id' => $request->profile_id, // Admin = 1, Manager = 2
        ]);

        return response()->json($user, 201);
    }


    public function loginCustomer(Request $request)
    {

        $requestValidated = $request->validate([
            'email_or_phone' => 'required|string',
            'password' => 'required|string|min:8'
        ]);
        //dd($requestValidated);
        if ($requestValidated) {
            $user = User::where('profile_id', 3)
                ->where(function ($query) use ($request) {
                    $query->where('email', $request->email_or_phone)
                        ->orWhere('phone', $request->email_or_phone);
                })->first();

                if ( !$user && ! Hash::check($requestValidated['password'], $user->password)) {
            
                    return response()->json([
                        'email' => ['The provided credentials are incorrect.'],
                        //'error' => 'The provided credentials are incorrect.',
                ]);

            /*if (!$user || ! Hash::check($requestValidated['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);*/
            }
            $token = $user->createToken('api_token')->plainTextToken;

            //dd($user->id);

            return response()->json(['token' => $token, 'user' => $user]);
        }
    }

    public function loginAdminOrManager(Request $request)
    {
        //balise html especially img as pwd connect successfully -- to correct
        $requestValidated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        //dd($requestValidated);
        if ($requestValidated) {
            // Chercher par username et profil admin ou manager
            $user = User::where('username', $requestValidated['username'])
                ->whereIn('profile_id', [1, 2]) // Admin = 1, Manager = 2
                ->first();

                //dd(!$user);

            if ( !$user && ! Hash::check($requestValidated['password'], $user->password)) {
            
                return response()->json([
                    'error' => 'The provided credentials are incorrect.',
            ]);
                }
            /*if (! $user && ! Hash::check($requestValidated['password'], $user->password)) {
                throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);*/
            else{
                $token = $user->createToken('api_token')->plainTextToken;
                //dd($user);
                return response()->json(['token' => $token, 'user' => $user]);
            }  
        }else{
            return response()->json($request, 406)->with(['error' => 'Information de connexion invalides']);
            
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    //function to get User connected informations
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    //forgot-password
    //??

}




        /*$requestValidated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required_without:phone|string|email|max:255|unique:users',
            'phone' => 'required_without:email|string|max:15|unique:users',
            //'password' => 'required|string|min:8|confirmed',
            'password' => 'required|string|min:8',
        ]);*/

        //dd($requestValidated);
        //dd($request);

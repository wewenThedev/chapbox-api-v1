<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function registerCustomer(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required_without:phone|string|email|max:255|unique:users',
            'phone' => 'required_without:email|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'profile_id' => 3, // Customer
        ]);
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


    public function loginCustomer(Request $request){

        $requestValidated = $request->validate([
            'email_or_phone' => 'required|string',
            'password' => 'required|string|min:8'
        ]);

        if($requestValidated){
            $user = User::where('profile_id', 3)
                ->where(function($query) use ($request) {
                    $query->where('email', $request->email_or_phone)
                          ->orWhere('phone', $request->email_or_phone);
                })->first();

            if(!$user || ! Hash::check($requestValidated['password'], $user->password)){
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
    $token = $user->createToken('api_token')->plainTextToken;
    
    return response()->json(['token' => $token]); 
    }
    }

    public function loginAdminOrManager(Request $request){
        $requestValidated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        if($requestValidated){
        // Chercher par username et profil admin ou manager
        $user = User::where('username', $requestValidated['username'])
                    ->whereIn('profile_id', [1, 2]) // Admin = 1, Manager = 2
                    ->first();
    
        if (! $user || ! Hash::check($requestValidated['password'], $user->password)) {
            Throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        $token = $user->createToken('api_token')->plainTextToken;
    
        return response()->json(['token' => $token, 'user' => $user]);
    }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    //function to get User connected informations
    public function user(Request $request){
        return response()->json($request->user());
    }

}


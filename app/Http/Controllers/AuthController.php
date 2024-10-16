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
    public function register(StoreUserRequest $request){
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Return response()->json($user, 201);

    }

    public function login(Request $request){
//Conditions sur le chemin pour chaque type utilisateur - customer / admin / manager
        $requestValidated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8'
        ]);

        if($requestValidated){
            $user = User::where('email', $requestValidated['email'])->first();

            if(!$user || ! Hash::check($requestValidated['password'], $user->password)){
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
    $token = $user->createToken('api_token')->plainTextToken;
    
    return response()->json(['token' => $token]); 
    }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user(Request $request){
        return response()->json($request->user());
    }

}


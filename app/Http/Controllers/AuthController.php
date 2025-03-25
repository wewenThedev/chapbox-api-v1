<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginAdminOrManagerRequest;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    //dd($request->all());
       

            /*dd($request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            //'username' => 'string|unique:users|min:4|max:255',
            'phone' => 'required|string|max:15|unique:users',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            //'password' => 'required|string|min:8|confirmed',
            'profile_id' => 'required|exists:profiles,id',]));*/

        //if($request->validated()){
    public function registerCustomer(/*StoreUserRequest*/ Request $request)
    {
        
        $validator = Validator::make($request->all(),
            [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'phone' => 'required|string|max:15|unique:users',
                //'email' => 'nullable|string|email|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'profile_id' => 'required|in:3', // Admin ou Manager
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],406);
        }else{
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->firstname.''.$request->lastname.''.random_int(0,2000),
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'profile_id' => $request->profile_id, //3 -- Customer
            ]);
            //Fonction pour créer le panier au moment de la création du compte à la première ouverture de l'application
            //$oldCart = Cart::where('device_id', $request->device_id)->where('user_id', $user->id)->first();
            $oldCart = Cart::where('device_id', $request->device_id)->first();
            //dd($cart);
            if ($oldCart !== null && $oldCart->device_id !=='device-id') {
                $oldCart->user_id = $user->id;
                $oldCart->save();
            } else {
                $newCart = Cart::create([
                    'user_id' => $user->id,
                    //'device_id' => $request->device_id,
                    'device_id' => $request->device_id.''.$user->id,
                ]);
            }
    
            //$user->load(['cart', 'profile', 'picture']);
            $user->load('cart');

            return response()->json([
                'user' => $user,
                'cart' => $user->cart
            ], 201);
        }
        
    }

    public function registerAdminOrManager(Request $request)
    {

        /*$request->validate([
            'username' => 'required|string|max:255|unique:users',
            //'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);*/

        $validator = Validator::make($request->all(),
            [
                'username' => 'required|string|max:255|unique:users',
                //'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],406);
        }else{
            
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_id' => $request->profile_id, // Admin = 1, Manager = 2
        ]);
        
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json(['message'=> 'Inscription réussie','token' => $token, 'user' => $user], 201);

        //return response()->json($user, 201);
        }
    }

    /*
    	Admin
-	owenAdmin – sysAdmin33
	Manager
-	geraldineAgoss – caisseErevan1
-	maturinDag – superviseurMS2

	User
-	testSys – testLogin1 - sys@test.com
-	marcosAd  - testLogin2
-	claudel – customer45!
-	 [{"key":"email","value":"toboucharmel7@gmail.com","description":null,"type":"default","enabled":true,"equals":true}] 
-	
-	[{"key":"password","value":"designAndCode7","description":null,"type":"default","enabled":true,"equals":true}]

    */

                        /*if (!$user || ! Hash::check($requestValidated['password'], $user->password)) {
                            throw ValidationException::withMessages([
                                'email' => ['The provided credentials are incorrect.'],
                            ]);*/
                            
        //dd($requestValidated);
    public function loginCustomer(Request $request)
    {

        /*$requestValidated = $request->validate([
            'email_or_phone' => 'required|string',
            'password' => 'required|string|min:8'
        ]);*/

        $validator = Validator::make($request->all(),
        [
            'email_or_phone' => 'required|string',
            'password' => 'required|string|min:8'
    ]);
    if($validator->fails()){
        return response()->json(['errors' => $validator->errors()],406);
    }else{
        $requestValidated = $request->validated();
        try {
            if ($requestValidated) {
                $user = User::where('profile_id', 3)
                    ->where(function ($query) use ($request) {
                        $query->where('email', $request->email_or_phone)
                            ->orWhere('phone', $request->email_or_phone);
                    })->first();
                if ($user) {
                    if (!$user && !Hash::check($requestValidated['password'], $user->password)) {

                        return response()->json([
                            'email' => ['The provided credentials are incorrect.'],
                        ]);
                    }
                    /*$token = $user->createToken('api_token')->plainTextToken;
                    return response()->json(['token' => $token, 'user' => $user]);*/

                    $user->load('cart');
                    return response()->json(['user' => $user], 200);
                
                }
            } else {
                return response()->json(['error' => 'Informations de connexion invalides', 'request' => $requestValidated], 406);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    }
    public function loginAdminOrManager(/*Request*/ LoginAdminOrManagerRequest $request)
    {
        /*
             "token": "12|O7UQgFMHbjPWXJ9ZcLMqFGcbPg1cLMfKo6Lionr7ce154125",
    "user": {
        "id": 9,
        "firstname": "Jean",
        "lastname": "DOSSA",
        "username": "jdoss4",
        "phone": "68741263",
        "email": "jdossms@test.com",
         */
        $requestValidated = $request;
        //dd($requestValidated);
        //balise html especially img as pwd connect successfully -- to correct
        /*$requestValidated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);*/
        try {
            // Code susceptible de générer une erreur

            if ($requestValidated) {
                // Chercher par username et profil admin ou manager
                $user = User::with(['picture', 'cart', 'orders'])->where('username', $requestValidated['username'])
                    ->whereIn('profile_id', [1, 2]) // Admin = 1, Manager = 2
                    ->first();

                //dd(!$user);
                //dd($user);
                //cas où identifiant client saisis
                if ($user) {
                    if (!$user && !Hash::check($requestValidated['password'], $user->password)) {

                        return response()->json([
                            'error' => 'Les identifiants saisis sont incorrects. Vous n\'etes pas autorisés',
                            'username' => 'The provided credentials are incorrect.',
                        ]);
                    }
                    /*if (! $user && ! Hash::check($requestValidated['password'], $user->password)) {
                        throw ValidationException::withMessages([
                        'username' => ['The provided credentials are incorrect.'],
                    ]);*/ else {
                        $token = $user->createToken('api_token')->plainTextToken;
                        //dd($user);
                        return response()->json(['token' => $token, 'user' => $user, 'success' => 'Connexion réussie']);
                    }

                } else {
                    return response()->json(['error' => 'Informations de connexion invalides', 'request' => $requestValidated], 406);

                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue.',
                'error' => $e->getMessage()
            ], 500);
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

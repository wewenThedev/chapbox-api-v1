<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

use App\Models\User;
use App\Models\Profile;
use App\Models\Media;

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
    public function show(/*string $id*/ User $user)
    {
        //
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, /*string $id*/ User $user)
    {
        //
        $user->update($request->validated());
        return response()->json($user);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(/*string $id*/ User $user)
    {
        //
        $user->delete();
        return response()->json(null, 204);

    }

    public function getOrderHistory(){
        //return all the orders from a user
    }

    public function getCartProducts(){

    }

    public function getSavedAdresses(){

    }

    public function updateSavedAddress(string $id){

    }

    public function removeSavedAddress(string $id){

    }

    public function getProfilePicture(){

    }

}

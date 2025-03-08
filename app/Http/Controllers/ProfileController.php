<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\StoreProfileRequest;

use App\Models\Profile;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd(Profile::query());
        //
        //return Profile::all();
        //return Profile::paginate(2);

        $profile = Profile::all();
        return response()->json($profile, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfileRequest $request)
    {
        //
        $profile = Profile::create($request->validated());
        return response()->json($profile, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id /*Profile $profile*/)
    {
        $profile = Profile::findOrFail($id);
        return $profile;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, string $id /*Profile $profile*/)
    {
        $profile = Profile::findOrFail($id);
        $profile->update($request->validated());
        return response()->json($profile);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id /*Profile $profile*/)
    {
        $profile = Profile::findOrFail($id);
        $profile->delete();
        return response()->json(null, 204);

    }
}

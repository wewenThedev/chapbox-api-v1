<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupermarketRequest;
use App\Http\Requests\UpdateSupermarketRequest;
use App\Http\Resources\SupermarketResource;
use Illuminate\Http\Request;

use App\Models\Supermarket;
use App\Models\User;
use App\Models\Media;
use App\Models\Shop;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;

class SupermarketController extends Controller
{
    //Penser à exploiter les transactions pour garantir l'intégrité des données
    public function generateSupermarketLogoFilename($supermarket)
    {
        $timestamp = time();
        $extension = $this->getFileExtension($supermarket->logo); // Ex : .png, .jpg
        return 'supermarket_logo_' . $supermarket->id . '_' . $timestamp . $extension;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Supermarket::created([
            'id' => ,
            'name' => ,
            'description' => ,
            'denomination' => ,
            'rccm' => ,
            'ifu' => ,
            'website' => ,
            'address_id' => ,
            'logo_id' => ,
            'market_manager_id' => ,
        ]);
        if(Auth::check()){
            if (auth()->user()->role == 'manager') {
                $supermarkets = Supermarket::where('manager_id', auth()->id())->get();
            } else {
                //$supermarkets = Supermarket::all();
                //$supermarkets = Supermarket::paginate(5);
            }
        }else{
            //return response()->json('please go and connect yourself');
            $supermarkets = Supermarket::all();
        }
        
        //return response()->SupermarketResource($supermarkets);
        return response()->json($supermarkets, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupermarketRequest $request)
    {
        Supermarket::create($request->validated());
        return response()->json(['message' => 'Supermarket created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supermarket = Supermarket::findOrFail($id);
        return response()->json($supermarket, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupermarketRequest $request, $id)
    {
        $supermarket = Supermarket::findOrFail($id);
        $supermarket->update($request->validated());

        return response()->json(['message' => 'Supermarket updated successfully'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $supermarket = Supermarket::findOrFail($id);
        $supermarket->delete();

        return response()->json(['message' => 'Supermarket deleted successfully']);
    }
}

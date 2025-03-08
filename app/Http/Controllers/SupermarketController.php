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

    /// scheduled task to updateSUpermarketInDbAddress(){}


    ///search a supermarket by name or address task from maps()

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
        /*Supermarket::create([
            'name' => 'Erevan',
            'address_id' => 2,
            'logo_id' => 1,
            'market_manager_id' => 2,
        ]);
        Supermarket::create([
            'name' => 'Mont  Sinai',
            'address_id' => 3,
            'logo_id' => 5,
            'market_manager_id' => 8,
        ]);*/
        if(Auth::check()){
            if (auth()->user()->role == 'manager') {
                $supermarkets = Supermarket::where('manager_id', auth()->id())->get();
                //$supermarkets = Supermarket::where('manager_id', auth()->id())->paginate(3);
            } else {
                //$supermarkets = Supermarket::all();
                //$supermarkets = Supermarket::paginate(5);
            }
        }else{
            //return response()->json('please go and connect yourself');
            //$supermarkets = Supermarket::paginate(3);
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

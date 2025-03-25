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

public function shortListSupermarketsRegistered(){
    $supermarkets = Supermarket::with(['shops', 'address', 'logo'])->limit(4)->get();

    return response()->json(['supermarkets' => $supermarkets], 201);
}

public function listSupermarketsRegistered(){
    $supermarkets = Supermarket::with(['shops', 'address', 'logo'])->paginate(10);

    return response()->json(['supermarkets' => $supermarkets], 201);
}

/*
public function fetchShopsBySupermarket($supermarketId){

    $supermarket = Supermarket::find($supermarketId);

    if($supermarket){
        $supermarket->load(['shops', 'address', 'logo']);
        return response()->json($supermarket, 201);
    }else{
        return response()->json(['error' => 'Supermarché non trouvé'], 404);
    }
}*/

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
            } else if(auth()->user()->role == 'adlmin'){
                //$supermarkets = Supermarket::all();
                $supermarkets = Supermarket::with(['shops', 'address', 'logo'])->paginate(5);
            }else{
                $supermarkets = Supermarket::with(['shops', 'address', 'logo'])->paginate(10);
            }
        }else{
            //return response()->json('please go and connect yourself');
            //$supermarkets = Supermarket::paginate(3);
            $supermarkets = Supermarket::with(['shops', 'address', 'logo'])->get();
        }
        
        //return response()->SupermarketResource($supermarkets);
        return response()->json(['supermarkets' => $supermarkets], 200);
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
        $supermarket = Supermarket::find($id);

        if($supermarket){
        $supermarket->load(['shops', 'address', 'logo']);
        return response()->json($supermarket, 200);
    }else{
        return response()->json(['error' => 'Supermarché non trouvé'], 404);
    }
    }

    public function updateSupermarketsDescription(UpdateSupermarketRequest $request){
        $supermarkets = Supermarket::where('description', null)->get();

        foreach ($supermarkets as $supermarket) {
            $supermarket->description = $request->description;
            $supermarket->save();
        }

        return response()->json(['success'=> 'Descritpion des supermarchés mises à jour avec succès'],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupermarketRequest $request, $id)
    {
        $supermarket = Supermarket::find($id);

        if($supermarket){
            if($supermarket->update($request->validated())){

        $supermarket->load(['shops', 'address', 'logo']);

        return response()->json(['message' => 'Supermarket updated successfully'], 201);
    }else{
        return response()->json(['error' => 'Echec de la mise à jour des informations'], 404);
    }
//dd($user);
}else{
    return response()->json(['error' => 'Supermarché non trouvé'], 404);
}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        $supermarket = Supermarket::find($id);
        $supermarketToDelete = $supermarket;
        if($supermarket){
            if($supermarket->delete()){

        return response()->json(['message' => 'Supermarché '.$supermarketToDelete->name.' supprimé avec succès']);
    }else{
        return response()->json(['error' => 'Echec de la suppression du supermarché'], 404);
    }
//dd($supermarket);
}else{
    return response()->json(['error' => 'Supermarché non trouvé'], 404);
}
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\UpdateShopRequest;
use App\Http\Requests\StoreShopRequest;

use App\Models\Shop;
use App\Models\Product;
use App\Models\Address;
use App\Models\Media;
use App\Models\ShoppingDetails;
use App\Models\ShopProduct;

class ShopController extends Controller
{
    public function generateShopImageFilename($shop) {
        $timestamp = time();
        $extension = $this->getFileExtension($shop->image); // Ex : .png, .jpg
        return 'shop_image_' .$shop->getFullName().'_'. $shop->id . '_' . $timestamp . $extension;
    }

    public function shortListShops(){
        $shops = Shop::with(['shops', 'address', 'logo'])->limit(4)->get();
    
        return response()->json(['shops' => $shops], 201);
    }
    
    public function listShops(){
        $shops = Shop::with(['address', 'products', 'media', 'supermarket'])->paginate(10);
    
        return response()->json(['shops' => $shops], 201);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        
        //$shops = Shop::with(['address', 'products', 'media', 'supermarket'])->get();

        //$shops = Shop::all();
        $shops = Shop::paginate(2);

        return response()->json($shops, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {
        //
        $shop = Shop::create($request->validated());
        $shop->load(['address', 'products', 'media', 'supermarket']);

        return response()->json($shop, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $shop = Shop::find($id);

        if($shop){
        $shop->load(['address', 'products', 'media', 'supermarket']);

        return response()->json($shop, 200);
    }else{
        return response()->json(['error' => 'Boutique de supermarcché non trouvé'], 404);
    }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShopRequest $request, $id)
    {
        $shop = Shop::find($id);
        
        if($shop){
            if($shop->update($request->validated())){
        $shop->load(['address', 'products', 'media', 'supermarket']);


        return response()->json($shop, 201);
    }else{
        return response()->json(['error' => 'Echec de la mise à jour des informations'], 404);
    }
//dd($shop);
}else{
    return response()->json(['error' => 'Boutique de supermarché non trouvé'], 404);
}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shop = Shop::find($id);
        $shopToDelete = $shop;
        if($shop){
            if($shop->delete()){

        return response()->json(null, 204);
    }else{
        return response()->json(['error' => 'Echec de la suppression de la boutique de supermarché'], 404);
    }
//dd($supermarket);
}else{
    return response()->json(['error' => 'outique de Supermarché non trouvé'], 404);
}
    }
    
}

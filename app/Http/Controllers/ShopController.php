<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\UpdateShopRequest;
use App\Http\Requests\StoreShopRequest;

use App\Models\Shop;
use App\Models\Product;
use App\Models\Supermarket;
use App\Models\ShoppingDetails;
use App\Models\ShopProduct;

class ShopController extends Controller
{
    public function generateShopImageFilename($shop) {
        $timestamp = time();
        $extension = $this->getFileExtension($shop->image); // Ex : .png, .jpg
        return 'shop_image_' .$shop->getFullName().'_'. $shop->id . '_' . $timestamp . $extension;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*Shop::create([
            'city' => 'Cotonou',
            'phone' => '21212121',
            'address_id' => 5,
            'supermarket_id' => 1,
            'shop_manager_id' => 2,
        ]);*/
        
        $shops = Shop::all();
        //return Shop::paginate(2);
        return response()->json($shops, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {
        //
        $shop = Shop::create($request->validated());
        return response()->json($shop, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id /*Shop $shop*/)
    {
        $shop = Shop::findOrFail($id);
        return $shop;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShopRequest $request, string $id /*Shop $shop*/)
    {
        $shop = Shop::findOrFail($id);
        $shop->update($request->validated());
        return response()->json($shop);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id /*Shop $shop*/)
    {
        $shop = Shop::findOrFail($id);
        $shop->delete();
        return response()->json(null, 204);

    }
}

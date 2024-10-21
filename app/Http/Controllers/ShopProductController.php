<?php

namespace App\Http\Controllers;

use App\Models\ShopProduct;
use Illuminate\Http\Request;

class ShopProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //return ShopProduct::all();
        return ShopProduct::paginate(5);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shopProduct = ShopProduct::findOrFail($id);
        return $shopProduct;
    }


    /*
     * Store a newly created resource in storage.
     *
    public function store(StoreShopProductRequest $request)
    {
        //
        $shopProduct = ShopProduct::create($request->validated());
        return response()->json($shopProduct, 201);

    }
    
    /*
     * Update the specified resource in storage.
     *
    public function update(UpdateShopProductRequest $request, string $id)
    {
        $shopProduct = ShopProduct::findOrFail($id);
        $shopProduct->update($request->validated());
        return response()->json($shopProductMedia);

    }

    /*
     * Remove the specified resource from storage.
     *
    public function destroy(string $id)
    {
        $shopProduct = ShopProduct::findOrFail($id);
        $shopProduct->delete();
        return response()->json(null, 204);

    }
*/
}

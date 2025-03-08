<?php

namespace App\Http\Controllers;

use App\Models\ShopProduct;
use Illuminate\Http\Request;

use function Laravel\Prompts\error;

class ShopProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $shopProducts = ShopProduct::all();
        return response()->json($shopProducts, 200);

        //return ShopProduct::paginate(5);
    }

    public function getProductsByShop($shopId)
    {
        $shopProduct = ShopProduct::with(['shop', 'product'])->where('shop_id', $shopId)->get();
        //dd($shopProduct);
        return response()->json($shopProduct, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($shopId, $productId)
    {
        $shopProduct = ShopProduct::with(['shop', 'product'])->where('shop_id', $shopId)->where('product_id', $productId)->get();
        //dd($shopProduct);
        return response()->json($shopProduct, 200);
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
        return response()->json($shopProduct);

    }

    /*
     * Remove the specified resource from storage.
     */
    public function destroy($shopId, $productId)
    {
        $shopProduct = ShopProduct::where('shop_id', $shopId)->where('product_id', $productId)->get();
        $removedShopProduct = $shopProduct;
        //dd($shopProduct);
        if (!$shopProduct) {
            return response()->json(["error" => "Produit introuvable"], 404);
        } else {
            $shopProduct->delete();
            //dd($shopProduct);
            return response()->json(["message" => "Le produit ".$shopProduct->product->name." du supermarché ".$shopProduct->shop->fullName." supprimé avec succès"], 204);
        }
    }

    public function getNewProductsByShop($shopId){
        // to write
    }

    public function getNewProducts(){
        // to write
    }
}

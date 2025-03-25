<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\ShopProduct;
use App\Models\Shop;
use App\Models\Product;

use function Laravel\Prompts\error;

class ShopProductController extends Controller
{
    //générer une liste de produits 100% Béninois via chatgpt
    //download products images
    //download shops images
    //attach images to product
    //attach images to shop
    //test FedaPay API
    //test GoMaps response in this API
    //penser à générer les routes et fonctions pour les statistiques

    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    //dd($testImagesProducts);

        $shopProducts = ShopProduct::with(['shop', 'product', 'imagesProduct'])::orderBy('created_at', 'desc')->paginate(10); // 10 produits par page
        //$shopProducts = ShopProduct::all();

        //$shopProducts = ShopProduct::limit(10)->get();
        return response()->json($shopProducts, 200);

        //return ShopProduct::paginate(5);
    }

    public function latestShopProducts(){
        $latest = ShopProduct::limit(10)->orderByDesc('created_at')->get();
        return response()->json($latest, 200);
    }
    

    public function getProductsByShop($shopId)
    {
        $shopProducts = ShopProduct::with(['shop', 'product'])->where('shop_id', $shopId)->get();
        //dd($shopProducts);
        return response()->json($shopProducts, 200);
    }


    /**
     * Display the specified resource.
     */
    public function show($shopId, $productId)
    {
        $shopProduct = ShopProduct::with(['shop', 'product'])->where('shop_id', $shopId)->where('product_id', $productId)->get();
        //$shopProduct = ShopProduct::where('shop_id', $shopId)->where('product_id', $productId)->get();
        //dd($shopProduct);
        return response()->json(['shopProduct' => $shopProduct], 200);
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

    public function getImagesProduct($shopId, $productId){

    $imagesProducts = ShopProduct::with('imagesProduct')->where('shop_id', )->where('product_id', )->get();
    return response()->json( $imagesProducts,200);

    }

    /**
 * Récupère les nouveaux produits d'une boutique spécifique.
 * * Récupère les nouveaux produits pour une boutique donnée.
     * On suppose que le modèle Shop possède une relation "products" définie comme :
     * return $this->belongsToMany(Product::class, 'shop_products')->withPivot('price', 'stock', 'created_at');
     *
 *
 * @param  int  $shopId
 * @return \Illuminate\Http\JsonResponse
 */
public function getNewProductsByShop($shopId)
{
    $shop = Shop::find($shopId);
    if (!$shop) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Boutique introuvable.'
        ], 404);
    }

    // Récupérer les produits ajoutés au cours des 30 derniers jours pour une boutique donnée
    /*$newProducts = ShopProduct::where('shop_id', $shopId)
    ->where('created_at', '>=', now()->subDays(1)) // Produits ajoutés dans les 30 derniers jours
        ->orderBy('created_at', 'desc')
        ->get();*/

        //->where('created_at', '>=', now()->subDays(30)) // Produits ajoutés dans les 30 derniers jours
    
        // Récupère les produits associés à la boutique triés par date d'ajout décroissante (champ created_at de la table pivot)
        $newProducts = $shop->products()->where('created_at', '>=', now()->subDays(10))->orderBy('shop_products.created_at', 'desc')->get();


    return response()->json([
        'status'    => 'success',
        'shop'      => $shop->name,
        'count'     => $newProducts->count(),
        'data'      => $newProducts
    ], 200);
}

/**
 * Récupère les nouveaux produits de toutes les boutiques.
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function getNewShopProducts()
{
    // Récupérer les produits ajoutés au cours des 30 derniers jours, toutes boutiques confondues
    //$newProducts = ShopProduct::where('created_at', '>=', now()->subDays(30))
    $newProducts = ShopProduct::with(['shop', 'product'])->where('created_at', '>=', now()->subDays(1))
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'status'    => 'success',
        'count'     => $newProducts->count(),
        'data'      => $newProducts
    ], 200);
}



public function getLocalProducts()
{
    $keywords = [
        '100% Benin', 'béninois', 'benin', 'produit local',
        'produit du terroir', 'fabriqué au Bénin', 'produit au Bénin', 'made in benin', 'bénin', 'benin', 'béninois',
        '100% bénin', '100% béninois', 'produit au bénin',
        'fabriqué au bénin', 'au bénin', '100% produit localement', '100% local', 'local'
    ];
    
    $products = Product::where(function ($query) use ($keywords) {
        foreach ($keywords as $keyword) {
            $query->orWhere('description', 'LIKE', "%{$keyword}%")
            ->orWhere('name', 'LIKE', "%{$keyword}%");
        }
    })
    //->with(['shops', 'media']) // Charger les relations Shop et Media
    ->get();

    return $products;
}


    /**
 * Trouve les produits 100% béninois en recherchant des mots-clés spécifiques dans le nom ou la description.
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function findBenineseProducts()
{
    // Liste des mots-clés à rechercher (sans sensibilité à la casse) voir getLocalProducts

    //ShopProduct::where('product.description','LIKE','%'. $keywords .'%')->get();

    /*$products = ShopProduct::whereHas('product',function ($query) use ($keywords) {
        foreach ($keywords as $word) {
            $query->where('description', 'LIKE', '%' . strtolower($word) . '%');
        }
    })->orWhereHas('product',function ($query) use ($keywords) {
        foreach ($keywords as $word) {
            $query->where('name', 'LIKE', '%' . strtolower($word) . '%');
        }
    })->get();*/

    
    //$products = (new Product)->getLocalProducts();
    $products = $this->getLocalProducts();

    //mise à jour to return shopProduct with stock and price
    
    return response()->json([
        'status' => 'success',
        'total'  => $products->count(),
        'products'   => $products
    ], 200);
}

public function findLocalProductsImages($products){

}

}

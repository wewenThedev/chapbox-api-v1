<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\Brand;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Everything need middleware
     */

    /*Public function __construct()
    {
        $this->middleware('role:admin,manager')->except(['index', 'show']);
    }
*/

    public function generateProductImageFilename($product)
    {
        $timestamp = time();
        //what if there is a lot of image?
        $extension = $this->getFileExtension($product->image); // Ex : .png, .jpg
        return 'product_image_' . $product->id . '_' . $timestamp . $extension;
    }

    public function getLatestProducts(){
        // to write
        $latestProducts = Product::orderBy('created_at', 'desc')->limit(5);
        
        //$latestProducts = Product::orderBy('created_at', 'desc')->offset(5);
        
        return response()->json($latestProducts, 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(?Request $request)
    {
        /*$products = Product::withTrashed()->get();
        foreach ($products as $product) {
            $product->deleted_at = null;
            $product->save();
        }*/
        //$this->authorize('viewAny', Product::class); -- temporary

        if (1 === 1/*Auth::check()*/) {
            //if (/*Auth::user()->role == 'manager'*/) {
            if (1 == 0/*auth()->user()->role == 'manager'*/) {

                //$products = Product::where('manager_id', auth()->id())->paginate(5);//get();
                // Récupérer tous les produits avec leurs relations nécessaires
                //$query = Product::where('manager_id', auth()->id())->get();

                //$query = Product::where('manager_id', auth()->id())->get();
                $query = Product::where('manager_id', auth()->id())->paginate(10);
                dd($query);

                // Filtres de catégories
                if ($request->filled('category')) {
                    $query->where('category_id', $request->category);
                }

                // Filtres par prix
                if ($request->filled('min_price') && $request->filled('max_price')) {
                    $query->whereBetween('price', [$request->min_price, $request->max_price]);
                }

                // Filtrer par disponibilité (produits en stock uniquement)
                if ($request->filled('in_stock') && $request->in_stock == '1') {
                    $query->where('stock', '>', 0);
                }

                // Filtrer par marque
                if ($request->filled('brand')) {
                    $query->where('brand_id', $request->brand);
                }

                // Barre de recherche (par nom ou description)
                if ($request->filled('search')) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                }

                // Filtrer par note des utilisateurs
                if ($request->filled('rating')) {
                    $query->where('rating', '≥', $request->rating);
                }

                // Récupérer les produits filtrés
                //$products = $query->paginate(12); // 12 produits par page
                dd($query);
                //$products = Product::where('manager_id', auth()->id())->get();
                return response()->json($query, 200);
            } else {
                // Récupérer tous les produits avec leurs relations nécessaires
                $query = Product::query()->get();
                //dd($query);
                //dd($query->toSql());
                //dd($query->getModel());

                // Filtres de catégories
                if ($request->filled('category')) {
                    $query->where('category_id', $request->category);
                }

                // Filtres par prix
                if ($request->filled('min_price') && $request->filled('max_price')) {
                    $query->whereBetween('price', [$request->min_price, $request->max_price]);
                }

                // Filtrer par disponibilité (produits en stock uniquement)
                if ($request->filled('in_stock') && $request->in_stock == '1') {
                    $query->where('stock', '>', 0);
                }

                // Filtrer par marque
                if ($request->filled('brand')) {
                    $query->where('brand_id', $request->brand);
                }

                // Barre de recherche (par nom ou description)
                if ($request->filled('search')) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                }

                // Filtrer par note des utilisateurs
                if ($request->filled('rating')) {
                    $query->where('rating', '>=', $request->rating);
                }

                /*
                if ($request->filled('search')) {
                $query->where(function ($subQuery) use ($request) {
                $subQuery->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
                });
                }
                */

                // Récupérer les produits filtrés
                //$products = $query->where('id', '!==', null); // 12 produits par page
                //$products = $query->paginate(6);
                //dd($query);

                //$products = Product::paginate(5);

                return response()->json($query, 200);
            }
        }
        else {
            return response()->json(null, 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //
        $this->authorize('create');

        $product = Product::create($request->validated());
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        //dd($request);
        $product = Product::findOrFail($id);
        
        //dd($this->authorize('update', $product));

        //dd($request->validated());
        //dd($product->update($request->validated()));
        //dd(response()->json($product, 200));
        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $this->authorize('delete', $product);

        $product->delete();

        //return response()->json(['message' => 'Product deleted successfully'], 204);
        return response()->json(null, 204);
    }

    // Méthode pour vérifier le stock
    public function checkStock($shopId, $productId, $quantity)
    {
        $shopProduct = ShopProduct::where('shop_id', $shopId)
                                  ->where('product_id', $productId)
                                  ->first();

        if ($shopProduct && $shopProduct->stock >= $quantity) {
            return true;
        }

        return false;
    }

    // Méthode pour mettre à jour le stock après la commande
    public function updateStock($shopId, $productId, $quantity)
    {
        $shopProduct = ShopProduct::where('shop_id', $shopId)->where('product_id', $productId)->first();

        if ($shopProduct) {
            $shopProduct->stock -= $quantity;
            $shopProduct->save();
        }
    }
}




/*Product::create([
            'name' => ,
            'brand_id' => ,
            'description' => ,
            'weight' => , 
            'category_id' => ,
            'container_type' => ,
        ]);*/
        /*$products = Product::withTrashed()->get();
        foreach ($products as $product) {
            $product->deleted_at = null;
            $product->save();
        }*/
        //return Product::withTrashed()->get();
        //Product::onlyTrashed()->restore();
        //return Product::withoutGlobalScopes()->get();
        //return Product::all();
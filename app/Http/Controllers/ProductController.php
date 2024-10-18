<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Media;

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
    
    public function generateProductImageFilename($product) {
        $timestamp = time();
        //what if there is a lot of image?
        $extension = $this->getFileExtension($product->image); // Ex : .png, .jpg
        Return 'product_image_' . $product->id . '_' . $timestamp . $extension;
    }
    

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $this->authorize('viewAny', Product::class);
        if (auth()->user()->role == 'manager') {
            //$products = Product::where('manager_id', auth()->id())->paginate(5);//get();
            // Récupérer tous les produits avec leurs relations nécessaires
        //$query = Product::where('manager_id', auth()->id())->get();
        $query = Product::where('manager_id', auth()->id());

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
        $products = $query->paginate(12); // 12 produits par page

            //$products = Product::where('manager_id', auth()->id())->get();
        } else {
            // Récupérer tous les produits avec leurs relations nécessaires
        $query = Product::query();

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

        // Récupérer les produits filtrés
        $products = $query->paginate(12); // 12 produits par page

    }

            //$products = Product::all();
            //$products = Product::paginate(5);
        
        return response()->json($products, 200);
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
    public function show(/*string $id*/ Product $product)
    {
        //
        //$product = Product::findOrFail($id);
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, /*string $id*/ Product $product)
    {
        $this->authorize('update', $product);

        //$product = Product::findOrFail($id);

        $product->update($request->validated());
        return response()->json($product);

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

}

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

    /*public function __construct()
    {
        $this->middleware();
    }*/
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Product::all();
        //return Product::paginate(5);
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
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, /*string $id*/ Product $product)
    {
        $this->authorize('update', $product);

        $product->update($request->validated());
        return response()->json($product);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(/*string $id*/ Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();
        return response()->json(null, 204);

    }
}

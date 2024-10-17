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
    
    public function generateProductImageFilename($product) {
        $timestamp = time();
        //what if there is a lot of image?
        $extension = $this->getFileExtension($product->image); // Ex : .png, .jpg
        Return 'product_image_' . $product->id . '_' . $timestamp . $extension;
    }
    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (auth()->user()->role == 'manager') {
            //$products = Product::where('manager_id', auth()->id())->paginate(5);//get();
            $products = Product::where('manager_id', auth()->id())->get();
        } else {
            $products = Product::all();
            //$products = Product::paginate(5);
        }
        return $products;
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\UpdateBrandRequest;
use App\Http\Requests\StoreBrandRequest;

use App\Models\Brand;
use App\Models\User;

class BrandController extends Controller
{

    public function generateBrandLogoFilename($brand) {
        $timestamp = time();
        $extension = $this->getFileExtension($brand->logo); // Ex : .png, .jpg
        return 'brand_logo_' . $brand->id . '_' . $timestamp . $extension;
    }
    
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //return Brand::all();
        return Brand::paginate(2);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        //
        $brand = Brand::create($request->validated());
        return response()->json($brand, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id /*Brand $brand*/)
    {
        $brand = Brand::findOrFail($id);
        return $brand;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, string $id /*Brand $brand*/)
    {
        $brand = Brand::findOrFail($id);
        $brand->update($request->validated());
        return response()->json($brand);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id /*Brand $brand*/)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return response()->json(null, 204);

    }
}

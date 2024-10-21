<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Shop;
use App\Models\Media;
use App\Models\ShopMedia;

class ShopMediaMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //return ShopMedia::all();
        return ShopMedia::paginate(5);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id /*ShopMedia $ShopMediaMedia*/)
    {
        $ShopMediaMedia = ShopMedia::findOrFail($id);
        return $ShopMediaMedia;
    }


    /*
     * Store a newly created resource in storage.
     *
    public function store(StoreShopMediaRequest $request)
    {
        //
        $ShopMediaMedia = ShopMedia::create($request->validated());
        return response()->json($ShopMediaMedia, 201);

    }
    
    /*
     * Update the specified resource in storage.
     *
    public function update(UpdateShopMediaRequest $request, string $id)
    {
        $ShopMediaMedia = ShopMedia::findOrFail($id);
        $ShopMediaMedia->update($request->validated());
        return response()->json($ShopMediaMedia);

    }

    /*
     * Remove the specified resource from storage.
     *
    public function destroy(string $id)
    {
        $ShopMediaMedia = ShopMedia::findOrFail($id);
        $ShopMediaMedia->delete();
        return response()->json(null, 204);

    }
*/
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UpdateMediaRequest;
use App\Http\Requests\StoreMediaRequest;

use App\Models\Media;
use App\Models\User;
use App\Models\Brand;
use App\Models\Supermarket;
use App\Models\Shop;
use App\Models\Product;
use App\Models\PaymentMethod;

class MediaController extends Controller
{
    //Algorithme à vérifier mais boff

    Public function uploadMedia(Request $request, $type, $objectId) {
        $file = $request->file('media');
        
        If ($file->isValid()) {
            // Génère le nom du fichier selon le type de média
            Switch ($type) {
                Case 'brand':
                    $object = Brand::find($objectId);
                    $filename = $this->generateBrandLogoFilename($object);
                    Break;
                Case 'supermarket':
                    $object = Supermarket::find($objectId);
                    $filename = $this->generateSupermarketLogoFilename($object);
                    Break;
                Case 'product':
                    $object = Product::find($objectId);
                    $filename = $this->generateProductImageFilename($object);
                    Break;
                // Autres cas…
            }
            
            // Enregistre le fichier
            $filePath = $file->storeAs('uploads/' . $type, $filename, 'public');
            
            // Met à jour l'objet avec l'URL du fichier
            $object->update(['media_url' => $filePath]);
            
            return response()->json(['message' => 'Upload successful', 'file' => $filePath], 200);
        }
        
        return response()->json(['message' => 'Upload failed'], 400);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //return Media::all();
        return Media::paginate(2);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediaRequest $request)
    {
        //
        $media = Media::create($request->validated());
        return response()->json($media, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id /*Media $media*/)
    {
        $media = Media::findOrFail($id);
        return $media;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMediaRequest $request, string $id /*Media $media*/)
    {
        $media = Media::findOrFail($id);
        $media->update($request->validated());
        return response()->json($media);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id /*Media $media*/)
    {
        $media = Media::findOrFail($id);
        $media->delete();
        return response()->json(null, 204);

    }
}

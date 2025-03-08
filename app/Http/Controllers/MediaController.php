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
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;

class MediaController extends Controller
{
    //Algorithme à vérifier mais boff, ça marche pour les images à ajouter après la création de l'objet

    public function uploadMedia(Request $request, $type, $objectId) {
        $file = $request->file('media');
        
        if ($file->isValid()) {
            // Génère le nom du fichier selon le type de média
            switch ($type) {
                case 'brand':
                    $object = Brand::find($objectId);
                    $filename = $this->generateBrandLogoFilename($object);
                    break;
                case 'supermarket':
                    $object = Supermarket::find($objectId);
                    $filename = $this->generateSupermarketLogoFilename($object);
                    break;
                case 'shop':
                    $object = Shop::find($objectId);
                    $filename = $this->generateShopImageFilename($object);
                    break;
                case 'product':
                    $object = Product::find($objectId);
                    $filename = $this->generateProductImageFilename($object);
                    break;
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
        //https://www.erevanbenin.com/
        //https://www.supermarchemontsinai.com/
        //Storage::storeAs(),
        /*$originalImagePath = 'D:/Projet de soutenance/ressources/logos/docs/logo_fedaPay.png';

        $pathWithoutExtension = explode('.', $originalImagePath, 5);
        $explodeName = explode('/', $pathWithoutExtension[0],20);
        $index = count($explodeName);
        $imageName = $explodeName[$index-1];
        //$imagePath = Storage::putFileAs('public/uploads/logos', new File($originalImagePath),$imageName);
        $file = new UploadedFile($originalImagePath, $imageName, $pathWithoutExtension[1]);
        $imagePath = $file->storeAs('public/uploads/logos',$imageName.'.'.$pathWithoutExtension[1]);
        //dd($imagePath);
        Media::create([
            'name' => $imageName,
            'url' => $imagePath,
            'type' => $pathWithoutExtension[1],
            'description' => '',
        ]);*/
        $media = Media::paginate(5);
        //$media = Media::all();
        return response()->json($media, 200);
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

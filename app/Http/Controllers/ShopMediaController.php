<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreShopMediaRequest;
use App\Http\Requests\UpdateShopMediaRequest;
use App\Models\Shop;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use App\Models\ShopMedia;

class ShopMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $shopPictures = ShopMedia::all();
        return response()->json(['shopPictures' => $shopPictures], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $shopMedia = ShopMedia::findOrFail($id);
        return response()->json(['shopMedia' => $shopMedia], 200);
    }


    /*
     * Store a newly created resource in storage.
     */
    public function store(StoreShopMediaRequest $request)
    {
        //



        $ShopMediaMedia = ShopMedia::create($request->validated());
        return response()->json($ShopMediaMedia, 201);

    }
    
    /*
     * Update the specified resource in storage.
     */
    public function update(UpdateShopMediaRequest $request,$id)
    {
        $ShopMediaMedia = ShopMedia::findOrFail($id);
        $ShopMediaMedia->update($request->validated());
        return response()->json($ShopMediaMedia);

    }

    /*
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ShopMediaMedia = ShopMedia::findOrFail($id);
        $ShopMediaMedia->delete();
        return response()->json(null, 204);

    }

    /**
     * Récupère tous les médias associés à une boutique.
     *
     * @param  int  $shopId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getShopMedia($shopId)
    {
        $shop = Shop::find($shopId);
        if (!$shop) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Boutique introuvable.'
            ], 404);
        }

        // On suppose que la relation "media" est définie dans le modèle Shop.
        $mediaItems = $shop->media;

        return response()->json([
            'status' => 'success',
            'data'   => $mediaItems
        ], 200);
    }

    //pour une boutique précise
    public function viewAssociateNewMediaToOneShop($shopId){
        $shop = Shop::find($shopId);
        if (!$shop) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Boutique introuvable.'
            ], 404);
        }
    }
    public function viewAssociateNewMediaToShop(){
        $shops = Shop::all();
        dd($shops);
        return view('pages.addImages', ['shops' => $shops]);
    }

    /**
     * Associe de nouveaux médias à une boutique.
     * La requête doit contenir un tableau de fichiers sous la clé "images".
     * Chaque image est sauvegardée dans la table Media avant d'être associée à la boutique.
     *
     * @param  int  $shopId
     * @param  StoreShopMediaRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function associateNewMediaToShop(StoreShopMediaRequest $request)
    {
        $shop = Shop::find($request['shop_id']);
        if (!$shop) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Boutique introuvable.'
            ], 404);
        }

        $images = $request->file('images'); // On attend un tableau d'images
        $mediaItems = [];

        $i=0;
        foreach ($images as $image) {
            // Stockage du fichier image dans le disque "public" dans le dossier "media"
            $path = $image->store('media', 'public');

            // Création de l'enregistrement dans la table Media
            $media = Media::create([
                'name'        => $image->getClientOriginalName(),
                'url'         => $path,
                'type'        => $image->getClientMimeType(),
                'description' => $request->input('description', null),
            ]);

            // Association du media avec la boutique via la table pivot shop_media
            $shop->media()->attach($media->id);
            $mediaItems[] = $media;
            /*$mediaItems[$i] = $media;
            $i++;*/
        }

        return $mediaItems;
        /*return response()->json([
            'status'  => 'success',
            'message' => 'Médias associés à la boutique avec succès.',
            'data'    => $mediaItems
        ], 201);*/
    }

    /**
     * Trouve toutes les boutiques associées à un média donné.
     *
     * @param  int  $mediaId
     * @return \Illuminate\Http\JsonResponse
     */
    public function findShopsToMedia($mediaId)
    {
        $media = Media::find($mediaId);
        if (!$media) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Media introuvable.'
            ], 404);
        }

        // Recherche toutes les boutiques associées à ce media via la relation many-to-many.
        $shops = Shop::whereHas('media', function ($query) use ($mediaId) {
            $query->where('media.id', $mediaId);
        })->get();

        return response()->json([
            'status' => 'success',
            'data'   => $shops
        ], 200);
    }
}

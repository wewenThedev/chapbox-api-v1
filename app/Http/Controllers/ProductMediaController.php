<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProductMediaRequest;
use App\Models\Product;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class ProductMediaController extends Controller
{
    /**
     * Récupère tous les médias associés à un produit.
     *
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductMedia($productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Produit introuvable.'
            ], 404);
        }

        // On suppose que la relation "media" est définie dans le modèle Product.
        $mediaItems = $product->media;

        return response()->json([
            'status' => 'success',
            'data'   => $mediaItems
        ], 200);
    }

    /**
     * Associe de nouveaux médias à un produit.
     * La requête doit contenir un tableau de fichiers sous la clé "images".
     * Chaque image sera enregistrée dans la table Media avant d'être associée au produit.
     *
     * @param  int  $productId
     * @param  StoreProductMediaRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function associateNewMediaToProduct($productId, StoreProductMediaRequest $request)
    {
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Produit introuvable.'
            ], 404);
        }

        $images = $request->file('images'); // On attend un tableau d'images
        $mediaItems = [];

        $i=0;
        foreach ($images as $image) {
            // Stockage du fichier image dans le disque "public" dans le dossier "media"
            $path = $image->store('media', 'public');

            // Création de l'enregistrement Media
            $media = Media::create([
                'name'        => $image->getClientOriginalName(),
                'url'         => $path, // Vous pouvez utiliser asset() côté front si besoin
                'type'        => $image->getClientMimeType(),
                'description' => $request->input('description', null),
            ]);

            // Association du media avec le produit via la table pivot product_media
            $product->media()->attach($media->id);
            $mediaItems[] = $media;
            /*$mediaItems[$i] = $media;
            $i++;*/
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Médias associés au produit avec succès.',
            'data'    => $mediaItems
        ], 201);
    }

    /**
     * Trouve tous les produits associés à un média donné.
     *
     * @param  int  $mediaId
     * @return \Illuminate\Http\JsonResponse
     */
    public function findProductsToMedia($mediaId)
    {
        $media = Media::find($mediaId);
        if (!$media) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Media introuvable.'
            ], 404);
        }

        // On recherche tous les produits qui possèdent ce media via la relation many-to-many.
        $products = Product::whereHas('media', function ($query) use ($mediaId) {
            $query->where('media.id', $mediaId);
        })->get();

        return response()->json([
            'status' => 'success',
            'data'   => $products
        ], 200);
    }
}

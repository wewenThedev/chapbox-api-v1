<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    /*public function search(Request $request){

    }*/


    //à intégrer dans le controller originel
    /*public function uploadMedia(Request $request, $type, $objectId) {
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
    }*/
}

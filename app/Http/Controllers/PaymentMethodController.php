<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;

class PaymentMethodController extends Controller
{
    public function getFileExtension($requestLogoContent)
    {
        $extensionPosition = strpos($requestLogoContent->getMimeType(), '/');
        $extensionParts = str_split($requestLogoContent->getMimeType(), $extensionPosition + 1);
        return $extensionParts[1];
    }

    public function formatText(string $name)
    {
        return str_replace(' ', '_', $name);
    }

    public function generatePaymentMethodLogoFilename($request)
    {
        $timestamp = time();
        $extension = $this->getFileExtension($request->logo_id); // Ex : .png, .jpg
        return 'logo_' . $this->formatText($request->name) . '_payment_method_' . $timestamp . '.' . $extension;
    }

    //Cette fonction sauvegarde l'image dans le stockage local de l'API et renvoie le chemin 
    //vers ce fichier pour l'enregistrement dans la base de dpnnées -- les liens symboliques avaient déjà été affichés
    public function saveImage($request)
    {
        $file = $request->file('logo_id');
        try {
            if ($file->isValid()) {
                $fileName = $this->generatePaymentMethodLogoFilename($request);
                $filePath = $file->storeAs('uploads/payment_method_logos', $fileName, 'public');
                return $filePath;
            } else {
                return response()->json(['message' => 'Upload failed'], 400);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(/*$request*/)
    {
        //
        $query = PaymentMethod::query();

        //if($request->contains())
        //return 
        $allPaymentMethods = PaymentMethod::all();
        return response()->json([
            'paymentMethods' => $allPaymentMethods
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentMethodRequest $request)
    {
        //To add Celtiis and Card
        $requestValidated = $request->toArray();
        DB::beginTransaction();
        try {
            //saveImage return the file name
            $requestValidated['logo_id'] = $this->saveImage($request);

            $paymentMethodLogo = Media::create([
                'name' => 'Logo ' . $request->name,
                'url' => $requestValidated['logo_id'],
                'type' => 'image',
                'description' => $request->description
            ]);
            $requestValidated['logo_id'] = $paymentMethodLogo->id;
            //dd($requestValidated['logo_id']);
            ///reste à faire
            $paymentMethod = PaymentMethod::create($requestValidated);
            DB::commit();
            return response()->json($paymentMethod, 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $paymentMethod = PaymentMethod::find($id);
        if ($paymentMethod) {
            return response()->json(
                [
                    'success' => 'Méthode de paiement trouvéee',
                    'paymentMethod' => $paymentMethod
                ],
                20
            );
        } else {
            return response()->json(['error' => 'Méthode de paiement non trouvée'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, $id)
    {
        $paymentMethod = PaymentMethod::find($id);
        if ($paymentMethod) {
            if ($paymentMethod->update($request->validated())) {
                return response()->json(
                    [
                        'success' => 'Méthode de paiement mise à jour avec succès',
                        'paymentMethod' => $paymentMethod
                    ],
                    20
                );
            } else {
                return response()->json(['error' => 'Echec de la mise à jour de la méthode de paiement'], 400);
            }
            //dd($user);
        } else {
            return response()->json(['error' => 'Méthode de paiement non trouvée'], 404);
        }
        return response()->json($paymentMethod);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::find($id);
        if ($paymentMethod) {
            if ($paymentMethod->delete()) {
                return response()->json(
                    [
                        'success' => 'Méthode de paiement supprimée avec succès',
                    ],
                    204
                );
            } else {
                return response()->json(['error' => 'Echec de la suppression de la méthode de paiement'], 400);
            }
        } else {
            return response()->json(['error' => 'Méthode de paiement non trouvée'], 404);
        }
    }
}


/*
$cart = Cart::find($id);
        if ($cart) {
            if ($cart->update($request->validated())) {
                return response()->json(
                    [
                        'success' => 'Panier mis à jour avec succès',
                        'cart' => $cart
                    ],
                    20
                );
            } else {
                return response()->json(['error' => 'Echec de la mise à jour du panier'], 400);
            }
            //dd($user);
        } else {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }
*/
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
//use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator;

use App\Models\Supermarket;
use App\Models\User;
use App\Models\Order;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ShopProduct;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\ShoppingDetails;

use App\Models\PaymentMethod;
use App\Services\CartService;

use App\Http\Controllers\CartController;
use Ramsey\Uuid\Type\Decimal;

/*
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;*/


//revoir les relations ByShop, ByUser, BySupermarket, ByPaymentMethod

class OrderController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //if(AuthController::check()){
        if(auth()->user()){
            if (auth()->user()->role == 'manager') {


        //$productAdded = ShopProduct::select('price')->where('product_id', $this->product_id)->where('shop_id', $this->shop_id)->first();

                $shopInManagement = auth()->user()->shop->id;
                $managerId = auth()->user()->id;

                //trouver le supermarché et lister les commandes
                //ShoppingDetails::where('order_id', '!==', null)->where;
                //$orders = Order::where('user_id', auth()->user()->id)->paginate(10);
    
                $orders = Order::whereHas('shoppingDetails.shop', function($query) use ($managerId) {
                    $query->where('shop_manager_id', $managerId);
                })
                ->with(['shoppingDetails' => function($query) use ($managerId) {
                    $query->whereHas('shop', function($q) use ($managerId) {
                        $q->where('shop_manager_id', $managerId);
                    });
                }])
                ->get
                ();

            }elseif(auth()->user()->role == 'customer'){
                return to_route('user-orders');
            }
        }else{
            $orders = Order::paginate(10);
            //$orders = Order::all();
            
            //$orders = Order::limit(5)->get();
        }

        return response()->json($orders, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();
        DB::commit();
        DB::rollBack();
        try {
        } catch (\Exception $e) {
        }
        $requestValidated = $request->validated();

        $order = Order::create($requestValidated);

        return response()->json(['message' => 'Commande passée avec succès'], 201);
        //return response()->json(['message' => 'Order placed successfully']);
    }

    /**
     * Display the specified resource.
     */
    
    public function show($id)
    {
        //
        $order = Order::findOrFail($id);
        return response()->json($order, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, $id)
    {
        //
        $order = Order::findOrFail($id);
        $this->authorize('update', $order);
        $order->update($request);

        return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $order = Order::findOrFail($id);

        $this->authorize('delete', $order);

        $order->delete();

        return response()->json(['message' => 'Commande supprimée avec succès'], 204);
        //return response()->json(null, 204);
    }

    
    /**
     * Valide le panier et crée une commande.
     *
     * Paramètres attendus dans la requête :
     * - recovery_mode : string, requis, "pickup" (récupération en boutique) ou "delivery" (livraison à domicile)
     * - pickup_time   : requis si recovery_mode == "pickup" (format date/heure)
     * - delivery_time : requis si recovery_mode == "delivery" (format date/heure)
     * - delivery_address : requis si recovery_mode == "delivery"
     * - payment_method_id : ID de la méthode de paiement (doit exister dans payment_methods)
     *
     * Pour les utilisateurs non authentifiés (guest), les informations suivantes sont requises :
     * - guest_firstname, guest_lastname, guest_phone, guest_email
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function placeOrder(StoreOrderRequest $request)
    {
        //dd($request);
        /*
        // Définir les règles de validation de base
        $rules = [
            'recovery_mode'     => 'required|in:pickup,delivery',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ];

        // Selon le mode de récupération, valider les champs spécifiques
        if ($request->recovery_mode === 'pickup') {
            $rules['shipping_date'] = 'required|date';
        } elseif ($request->recovery_mode === 'delivery') {
            $rules['shipping_date']    = 'required|date';
            $rules['shipping_address'] = 'required|string';
        }

        // Pour les utilisateurs non authentifiés, exiger les informations guest
        if (!auth()->check()) {
            $rules['guest_firstname'] = 'required|string';
            $rules['guest_lastname']  = 'required|string';
            $rules['guest_phone']     = 'required|string';
            $rules['guest_email']     = 'required|email';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
        */
        //Après toutes les validations
        // Récupérer la méthode de paiement pour vérifier, par exemple, que le paiement en espèces est réservé à la récupération en boutique
        $paymentMethod = PaymentMethod::find($request->payment_method_id);
        if (strtolower($paymentMethod->name) === 'cash' && $request->recovery_mode !== 'pickup') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Le paiement en espèces est uniquement disponible pour une récupération en boutique.',
            ], 400);
        }

        // Récupérer le panier courant
        $cart = Cart::find($request->cart_id);

        if (!$cart) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Panier non trouvé.',
            ], 404);
        }

        // Récupérer les articles du panier
        $shoppingItems = ShoppingDetails::where('cart_id', $cart->id)->get();
        if ($shoppingItems->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Votre panier est vide.',
            ], 400);
        }

        // (Optionnel) Vérifier que tous les articles proviennent de la même boutique
        $firstShopId = $shoppingItems->first()->shop_id;
        foreach ($shoppingItems as $item) {
            if ($item->shop_id != $firstShopId) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Les produits dans le panier proviennent de boutiques différentes.',
                ], 400);
            }
        }
        //??Pour une comande déjà initiée puis abandonnée au niveau du paiement 
        // Création de la commande
        $order = new Order();
        $order->recovery_mode = $request->recovery_mode;
        // Calculer le coût total HT (total_ttc étant ajustable dans le model selon les taxes et le modèle économique)
        /*$totalHT = $shoppingItems->sum(function ($item) {
            return $item->quantity * $item->cost;
        });*/

        $cartController = new CartController(new CartService());
        $totalHT = $cartController->getCartTotal($cart->id);
        //$totalHT = $request->total_ht;
        //$totalTTC = (1.05)*$totalHT;
        //Recalcule du prix si code_promo
        $order->total_ht =  $totalHT;
        //dd($order->total_ht);
        $order->total_ttc = $order->calculateTotalTtc();
        //dd($order->total_ttc);
        $order->ordering_date = now();

        // Attention : ici, La commande doit contenir plusieurs articles, le schéma de la base pourra nécessiter une refonte.
        //Je viens de faire la refonte donc je dois modifier le reste --- Ce sont les ShoppingDetails qui doivent etre mis à jour

        //$order->shopping_details_id = $shoppingItems->first()->id;

        //$order->user_id = auth()->check() ? auth()->id() : null;

        $order->user_id = $request->user_id;

        if (!auth()->check() || !isset($request->user_id)) {
            $order->guest_firstname = $request->guest_firstname;
            $order->guest_lastname  = $request->guest_lastname;
            $order->guest_phone     = $request->guest_phone;
            $order->guest_email     = $request->guest_email;
        }

        // En fonction du mode de récupération, renseigner shipping_date et shipping_address
        if ($request->recovery_mode === 'pickup') {
            $order->shipping_date = $request->shipping_date; //==pickup_time
            // L'adresse de livraison est l'adresse de la boutique pour la récupération en boutique 
            $order->shipping_address = $shoppingItems->first()->shop->getFullName();
        } else {
            $order->shipping_date = $request->shipping_date; //==delivery_time
            $order->shipping_address = $request->shipping_address; //==delivery_address
        }

        $order->status = 'pending';

        // Création de la commande et vidage du panier dans une transaction
        DB::beginTransaction();
        try {
            $order->save();

            foreach ($shoppingItems as $shoppingItem) {
                $shoppingItem->order_id = $order->id;
                $shoppingItem->save();
            }

            $order->load(['shoppingDetails', 'payments', 'user']);

            // Une fois l'ordre créé, le panier demeure intact tant que l'utilisateur ne paye pas la commande 
            //ou ne supprime pas lui-meme les articles du panier
            //Donc c'est après le paoement que je vide le panier  --- clearCart de mon CartController
            //ShoppingDetails::where('cart_id', $cart->id)->delete();
            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Commande passée avec succès.',
                'data'    => $order,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Erreur lors de la création de la commande.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Affiche les détails d'une commande.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function show($orderId)
    {
        $order = Order::with('shopping_details')->find($orderId);

        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Commande non trouvée.'
            ], 404);
        }

        
        if (auth()->check()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $order,
        ], 200);
    }*/

    /**
     * Annule une commande si son statut est "en attente" ou "paiement échoué".
     *
     * @param  Request  $request
     * @param  int  $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelOrder(Request $request, $orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Commande non trouvée.'
            ], 404);
        }

        // S'assurer que la commande appartient à l'utilisateur (ou est un guest)
        if (!(auth()->user()->profile === 1 || auth()->user()->profile === 2)) {
            if (auth()->check() && $order->user_id !== auth()->id()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
        }

        // On peut annuler uniquement si la commande est en attente ou si le paiement a échoué
        if (!in_array($order->status, ['pending', 'failed'/*, 'sucessful'*/])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'La commande ne peut pas être annulée dans ce statut.'
            ], 400);
        }

        //Si la commande est passée et moins de 10 minutes après on veut annuler, c'est possible pour delivery
        //Si la commande est passée et moins de 30 minutes après on veut annuler, c'est possible pour pickup 
        //-- différence entre shipping date et ordering date

        $order->status = 'canceled';
        $order->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Commande annulée avec succès.',
            'data'    => $order,
        ], 200);
    }

    /**
     * Met à jour le statut d'une commande (destiné aux actions administratives ou de suivi).
     *
     * Paramètres attendus :
     * - status : valeur parmi "en attente", "en cours", "terminée", "annulée", "paiement échoué"
     *
     * @param  Request  $request
     * @param  int  $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrderStatus(UpdateOrderRequest $request, $orderId)
    {
        /*$validator = Validator::make($request->all(), [
            'status' => 'required|in:en attente,    en cours,terminée,annulée,paiement échoué',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
            ], 422);
        }*/

        //dd($request);

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Commande non trouvée.'
            ], 404);
        }
        DB::beginTransaction();
        try {
            // Ici, vous pouvez ajouter des vérifications supplémentaires (par exemple, autorisation d'administration)
            $order->status = $request->status;
            $order->save();
            DB::commit();

            //broadcast(new OrderStatusUpdated($order));

            return response()->json([
                'status'  => 'success',
                'message' => 'Statut de la commande mis à jour.',
                'data'    => $order,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }
}

/*
Si ton backend est en **Laravel (PHP)** et que tu veux le connecter à ton **application Flutter Web**, tu vas sûrement rencontrer un problème de **CORS (Cross-Origin Resource Sharing)**. Voici comment le résoudre et tester correctement ton application.

---

## ✅ **1. Activer CORS dans Laravel**  
Dans Laravel, tu peux configurer CORS avec le middleware `fruitcake/laravel-cors`.  

### **Étape 1 : Installer le package CORS**
Si ce n'est pas encore fait, installe le package **Laravel CORS** avec Composer :  
```sh
composer require fruitcake/laravel-cors
```

### **Étape 2 : Configurer CORS**
Ouvre le fichier **`config/cors.php`** (si le fichier n'existe pas, crée-le avec `php artisan vendor:publish --tag="cors"`), puis mets cette configuration :  

```php
return [
    'paths' => ['api/*'],  // Applique CORS sur toutes les routes API
    'allowed_methods' => ['*'],  // Autorise toutes les méthodes (GET, POST, PUT, DELETE)
    'allowed_origins' => ['*'],  // Autorise tous les domaines (PAS SÉCURISÉ EN PROD)
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],  // Autorise tous les headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

**⚠️ Sécurité en production**  
Si ton backend est en production, ne mets pas `'*'` dans `allowed_origins`. Remplace-le par l’URL de ton frontend :  
```php
'allowed_origins' => ['https://ton-site-flutter.com'],
```

---

## ✅ **2. Activer CORS dans le middleware de Laravel**  
Dans le fichier **`app/Http/Kernel.php`**, assure-toi d’ajouter le middleware CORS dans `$middleware` :  

```php
protected $middleware = [
    \Fruitcake\Cors\HandleCors::class,
    // Autres middlewares...
];
```

Puis, dans `$middlewareGroups['api']`, ajoute aussi `\Fruitcake\Cors\HandleCors::class` si ce n'est pas déjà fait.

---

## ✅ **3. Vérifier si l’API Laravel fonctionne avec Flutter Web**  
Après avoir configuré CORS, **teste ton API** avec **Postman** ou en ouvrant l’URL dans le navigateur :  
```sh
http://localhost:8000/api/ton-endpoint
```
Si elle répond correctement, c’est bon.

---

## ✅ **4. Faire un appel API depuis Flutter Web**  
Utilise le package `http` pour interagir avec ton backend Laravel :

```dart
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class ApiTestScreen extends StatefulWidget {
  @override
  _ApiTestScreenState createState() => _ApiTestScreenState();
}

class _ApiTestScreenState extends State<ApiTestScreen> {
  String data = "Chargement...";

  Future<void> fetchData() async {
    try {
      final response = await http.get(Uri.parse("http://localhost:8000/api/data"));

      if (response.statusCode == 200) {
        setState(() {
          data = jsonDecode(response.body)['message']; // Adapter selon ta réponse API
        });
      } else {
        setState(() {
          data = "Erreur: ${response.statusCode}";
        });
      }
    } catch (e) {
      setState(() {
        data = "Échec de la connexion: $e";
      });
    }
  }

  @override
  void initState() {
    super.initState();
    fetchData();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Test API Backend")),
      body: Center(child: Text(data)),
    );
  }
}
```

---

## ✅ **5. Compiler Flutter Web et tester**  
Lance ton application Flutter Web avec :  
```sh
flutter run -d chrome --web-renderer=html
```

Si **CORS bloque encore les requêtes**, essaie de **désactiver temporairement la sécurité CORS** dans Chrome pour tester :  

### **Sur Windows :**  
Ouvre `cmd` et exécute :  
```sh
chrome.exe --disable-web-security --user-data-dir="C:\chrome-dev"
```

### **Sur macOS :**  
Dans le Terminal :  
```sh
open -na "Google Chrome" --args --disable-web-security --user-data-dir="/tmp/chrome-dev"
```

⚠️ **Ne fais pas ça en production**, c’est juste pour tester en local.

---

## ✅ **Résumé**
✔ **Active CORS dans Laravel** (`config/cors.php`).  
✔ **Ajoute le middleware CORS** dans `app/Http/Kernel.php`.  
✔ **Teste ton API avec Postman** avant Flutter.  
✔ **Utilise `http` dans Flutter** pour appeler ton API.  
✔ **Lance Flutter Web avec `flutter run -d chrome --web-renderer=html`**.  
✔ **Si besoin, désactive temporairement CORS dans Chrome** pour tester.  

Essaie ça et dis-moi si tu rencontres encore des problèmes ! 🚀
*/
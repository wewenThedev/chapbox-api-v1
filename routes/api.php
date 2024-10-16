<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingDetailsController;
use App\Http\Controllers\ShopProductController;
use App\Http\Controllers\SupermarketController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ShopController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');


//Routes pour l'authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Routes pour les utilisateurs
Route::apiResource('users', UserController::class);

// Routes pour les supermarchés
Route::apiResource('supermarkets', SupermarketController::class);

// Routes pour les produits
Route::apiResource('products', ProductController::class);

// Routes pour les commandes
Route::apiResource('orders', OrderController::class);

// Routes pour les catégories
Route::apiResource('categories', CategoryController::class);

// Routes pour les marques
Route::apiResource('brands', BrandController::class);

// Routes pour les médias
Route::apiResource('media', MediaController::class);

// Routes pour les notifications
Route::apiResource('notifications', NotificationController::class);

// Routes pour les avis
//Route::apiResource('feedbacks', FeedbackController::class);

// Routes pour les paiements
Route::apiResource('payments', PaymentController::class);

// Routes pour les cartes
Route::apiResource('carts', CartController::class);

// Routes pour les détails de shopping
Route::apiResource('shopping-details', ShoppingDetailsController::class);

// Routes pour les promotions
Route::apiResource('promos', PromoController::class);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeedBackController;
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

//
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ManagerDashboardController;

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
//Route::post('/register', [AuthController::class, 'register']);

Route::post('/register/admin-manager', [AuthController::class, 'registerAdminOrManager']);
Route::post('/register/customer', [AuthController::class, 'registerCustomer']);

Route::post('/login/admin-manager', [AuthController::class, 'loginAdminOrController']);
Route::post('/login/customer', [AuthController::class, 'loginCustomer']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Obtenir les infos de l'utilisateur connecté
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');


// Routes pour les utilisateurs
Route::apiResource('users', UserController::class);
//Route::resource('users', UserController::class);

// Routes pour les commandes
Route::apiResource('orders', OrderController::class);
//Route::resource('orders', OrderController::class);

// Routes pour les marques
Route::apiResource('brands', BrandController::class);
//Route::resource('brands', BrandController::class);

// Routes pour les médias
Route::apiResource('media', MediaController::class);
//Route::resource('media', MediaController::class);

// Routes pour les notifications
Route::apiResource('notifications', NotificationController::class);
//Route::resource('notifications', NotificationController::class);

// Routes pour les avis
Route::apiResource('feedbacks', FeedbackController::class);
//Route::resource('feedbacks', FeedbackController::class);

// Routes pour les paiements
Route::apiResource('payments', PaymentController::class);
//Route::resource('payments', PaymentController::class);

// Routes pour les cartes
Route::apiResource('carts', CartController::class);
//Route::resource('carts', CartController::class);

// Routes pour les détails de shopping
Route::apiResource('shopping-details', ShoppingDetailsController::class);
//Route::resource('shopping-details', ShoppingDetailsController::class);

// Routes pour les promotions
Route::apiResource('promos', PromoController::class);

///routes resource

// Routes pour les profiles
Route::apiResource('profiles', ProfileController::class);

// Routes pour les catégories
Route::apiResource('categories', CategoryController::class);

// Routes pour les produits
//Route::apiResource('products', ProductController::class);
//Route::resource('products', ProductController::class);

// Routes pour les supermarchés
//temporaire
//Route::apiResource('supermarkets', SupermarketController::class);
//Route::resource('supermarkets', SupermarketController::class);

//routes avec middleware

Route::middleware('auth:sanctum')->group(function() {
    Route::group(['middleware' => 'role:admin'], function() {
        //Route:resource à changer avec ROute:apiResource vu que je gère API uniquement. Resource crée les routes create et edit
        Route::resource('supermarkets', SupermarketController::class);
    });
    Route::group(['middleware' => 'role:manager'], function() {
        Route::get('supermarkets', [SupermarketController::class, 'index']);
    });
    Route::group(['middleware' => 'role:customer'], function() {
        Route::get('supermarkets', [SupermarketController::class, 'index']);
    });
});

Route::middleware('auth:sanctum')->group(function() {
    Route::group(['middleware' => 'role:admin'], function() {
        Route::resource('products', ProductController::class);
    });
    Route::group(['middleware' => 'role:manager'], function() {
        Route::get('products', [ProductController::class, 'index']);
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::delete('products/{id}', [ProductController::class, 'destroy']);
    });
    Route::group(['middleware' => 'role:customer'], function() {
        Route::get('products', [ProductController::class, 'index']);
    });
});

Route::middleware('auth:sanctum')->group(function() {
    Route::group(['middleware' => 'role:admin'], function() {
        Route::get('orders', [OrderController::class, 'index']);
    });
    Route::group(['middleware' => 'role:manager'], function() {
        Route::get('orders', [OrderController::class, 'index']);

        //update Order Status
        Route::put('orders/{id}', [OrderController::class, 'update']);
    });
    Route::group(['middleware' => 'role:customer'], function() {
        Route::post('orders', [OrderController::class, 'store']);
        Route::get('orders/{id}', [OrderController::class, 'show']);
    });
});



//dashboard Admin

Route::middleware('auth:sanctum')->group(function() {
    // Routes pour l'admin
    Route::group(['middleware' => 'role:admin'], function() {
        Route::get('admin/stats', [AdminDashboardController::class, 'getStats']);
        //Route::get('admin/financial-reports', [AdminDashboardController::class, 'getFinancialReports']);
        Route::get('admin/kpi', [AdminDashboardController::class, 'getKPI']);
        //Route::post('admin/export-reports', [AdminDashboardController::class, 'exportReports']);
    });

    // Routes pour le manager
    Route::group(['middleware' => 'role:manager'], function() {
        Route::get('manager/stats', [ManagerDashboardController::class, 'getStats']);
        //Route::get('manager/financial-reports', [ManagerDashboardController::class, 'getFinancialReports']);
        Route::get('manager/kpi', [ManagerDashboardController::class, 'getKPI']);
    });
});


///Routes de dashboard à modifier pour faire correspondre aux controllers des Models
/*
Route::get('/dashboard/total-sales', [DashboardController::class, 'totalSales']);
Route::get('/dashboard/sales-by-day', [DashboardController::class, 'salesByDay']);
Route::get('/dashboard/sales-by-product', [DashboardController::class, 'salesByProduct']);
Route::get('/dashboard/sales-by-shop', [DashboardController::class, 'salesByShop']);
Route::get('/dashboard/anonymous-users', [DashboardController::class, 'anonymousUsers']);
Route::get('/dashboard/authenticated-users', [DashboardController::class, 'authenticatedUsers']);
Route::get('/dashboard/active-sessions', [DashboardController::class, 'activeSessions']);
Route::get('/dashboard/most-viewed-products', [DashboardController::class, 'mostViewedProducts']);
Route::get('/dashboard/cart-conversion-rate', [DashboardController::class, 'cartConversionRate']);
Route::get('/dashboard/total-orders', [DashboardController::class, 'totalOrders']);
Route::get('/dashboard/orders-by-user', [DashboardController::class, 'ordersByUser']);
*/


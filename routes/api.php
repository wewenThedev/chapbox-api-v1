<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
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
use App\Models\PaymentMethod;
use App\Models\ShopProduct;
use Illuminate\Routing\Route as RoutingRoute;

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


// Obtenir les infos de l'utilisateur connecté
/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

Route::get('/test-invoice', function () {
    return view('invoices.test_pdf');
});

//Routes pour l'authentification
//Route::post('/register', [AuthController::class, 'register']);

Route::post('/register/admin-manager', [AuthController::class, 'registerAdminOrManager'])->name('register.dashboard');
Route::post('/register/customer', [AuthController::class, 'registerCustomer'])->name('register.customer');

Route::post('/login/admin-manager', [AuthController::class, 'loginAdminOrManager'])->name('login.dashboard');
Route::post('/login/customer', [AuthController::class, 'loginCustomer'])->name('login.customer');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

// Routes pour les utilisateurs
Route::apiResource('users', UserController::class);


//{userId}
Route::get('users/{id}/my-cart', [UserController::class, 'getUserCartProducts'])->name('user-cart-content');
Route::get('users/{id}/orders-history', [UserController::class, 'getUserOrdersHistory'])->name('user-orders');


// Routes pour les commandes
Route::apiResource('orders', OrderController::class);
Route::post('orders/new', [OrderController::class, 'placeOrder']);
Route::put('orders/{orderId}/update-status', [OrderController::class, 'updateOrderStatus']);
Route::put('orders/{orderId}/cancel', [OrderController::class, 'cancelOrder']);

//Route pour les adresses
/*Route::apiResource('addresses', [AddressController::class]);
Route::get('addresses/reverse-geocode', [AddressController::class, 'reverseGeocode']);
Route::get('addresses/places', [AddressController::class, 'nearbyPlaces']);
Route::get('addresses/directions', [AddressController::class, 'getDirections']);
Route::get('addresses/text-search', [AddressController::class, 'textSearch']);
Route::get('addresses/places-details', [AddressController::class, 'placesDetails']);

/*Route::controller(AddressController::class)->group(function(){

});*/

// Routes pour les marques
Route::apiResource('brands', BrandController::class);

// Routes pour les médias
Route::apiResource('media', MediaController::class);

//-
Route::get('media/create/addImages', function(){
    return view('pages.addImages');
});
//Route::post('media/store',  [MediaController::class, 'store']);
Route::post('media/store',  [PaymentMethodController::class, 'store']);


// Routes pour les notifications
Route::apiResource('notifications', NotificationController::class);

// Routes pour les avis
Route::apiResource('feedbacks', FeedbackController::class);

// Routes pour les paiements
Route::apiResource('payments', PaymentController::class);

// Routes pour les paiements
Route::apiResource('payment-methods', PaymentMethodController::class);

// Routes pour les cartes
Route::apiResource('carts', CartController::class);

Route::post('carts/{cartId}/update-product-in-cart', [CartController::class, 'updateProductInCart']);
Route::get('carts/{cartId}/total-cost', [CartController::class, 'getCartTotal']);

//gestion du panier
Route::post('/cart/create', [CartController::class, 'createCart']);
Route::post('/cart/add', [CartController::class, 'addItemToCart']);
Route::post('/cart/remove', [CartController::class, 'removeItemFromCart']);
Route::get('/cart', [CartController::class, 'viewCart']);

// Routes pour les détails de shopping
Route::apiResource('shopping-details', ShoppingDetailsController::class);

// Routes pour les promotions
Route::apiResource('promos', PromoController::class);

///routes resource

// Routes pour les profiles
Route::apiResource('profiles', ProfileController::class);

// Routes pour les catégories
Route::apiResource('categories', CategoryController::class);

// Routes pour les produits
Route::apiResource('products', ProductController::class);

// Routes pour les supermarchés
Route::apiResource('supermarkets', SupermarketController::class);

// Routes pour les shops - boutiques
Route::apiResource('shops', ShopController::class);

//Route pour voir la liste de chaque shop avec ses produits
Route::get('shops/products', [ShopProductController::class, 'index']);

//Route pour voir les produits qui concernent une shop en particulier
Route::get('shops/{shopId}/products', [ShopProductController::class, 'getProductsByShop']);
Route::get('shops/{shopId}/products/{productId}', [ShopProductController::class, 'show']);

Route::delete('shops/{shopId}/products/{productId}', [ShopProductController::class, 'destroy']);

//routes avec middleware

/*
Route::middleware('auth:sanctum')->group(function() {
    Route::group(['middleware' => 'role:admin'], function() {
        //Route:resource à changer avec Route:apiResource vu que je gère API uniquement. Resource crée les routes create et edit
        //('supermarkets', SupermarketController::class)->except(['index', 'show']);
        Route::apiResource('supermarkets', SupermarketController::class);
    });
    Route::group(['middleware' => 'role:manager'], function() {
        Route::get('supermarkets', [SupermarketController::class, 'index']);
    });
    Route::group(['middleware' => 'role:customer'], function() {
        Route::get('supermarkets', [SupermarketController::class, 'index']);
    });  
});*/

/*Route::middleware('auth:sanctum')->group(function() {
    Route::group(['middleware' => 'role:admin'], function() {
        //Route:resource à changer avec Route:apiResource vu que je gère API uniquement. Resource crée les routes create et edit
        Route::apiResource('shops', ShopController::class);
    });
    Route::group(['middleware' => 'role:manager'], function() {
        Route::get('shops', [ShopController::class, 'index']);
        //what to update?
        Route::post('shops', [ShopController::class, 'update']);
    });
    Route::group(['middleware' => 'role:customer'], function() {
        Route::get('shops', [ShopController::class, 'index']);
    });
});*/

/*Route::middleware('auth:sanctum')->group(function() {
    Route::group(['middleware' => 'role:admin'], function() {
        Route::apiResource('products', ProductController::class);
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
});*/

//Route::get('products', [ProductController::class, 'index']);
        

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

Route::get('orders', [OrderController::class, 'index']);
//ajouter getOrderHistory ...

//to test
route::post('/payment/initiate', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');
route::get('/payment/status', [PaymentController::class, 'getPaymentStatus'])->name('payment.status');
//-

Route::get('/fedapay/token', [PaymentController::class, 'generatePaymentToken']);
Route::post('/fedapay/payment', [PaymentController::class, 'processPayment']);

//--


//Regénérer le PDF du dictionnaire de données de la BD chapbox

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

Route::get('/dashboard/total-sales', [AdminDashboardController::class, 'totalSales']);
//Route::get('/dashboard/sales-by-day', [AdminDashboardController::class, 'salesByDay']);
Route::get('/dashboard/sales-by-product', [AdminDashboardController::class, 'salesByProduct']);
Route::get('/dashboard/sales-by-shop', [AdminDashboardController::class, 'salesByShop']);
Route::get('/dashboard/anonymous-users', [AdminDashboardController::class, 'anonymousUsers']);
Route::get('/dashboard/authenticated-users', [AdminDashboardController::class, 'authenticatedUsers']);
//Route::get('/dashboard/active-sessions', [AdminDashboardController::class, 'activeSessions']);
Route::get('/dashboard/most-viewed-products', [AdminDashboardController::class, 'mostViewedProducts']);
Route::get('/dashboard/cart-conversion-rate', [AdminDashboardController::class, 'cartConversionRate']);
Route::get('/dashboard/total-orders', [AdminDashboardController::class, 'totalOrders']);
Route::get('/dashboard/orders-by-user', [AdminDashboardController::class, 'ordersByUser']);



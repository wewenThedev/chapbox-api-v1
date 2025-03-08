<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
//use App\Models\Session;
use App\Models\User;
use App\Models\Cart;

class AdminDashboardController extends Controller
{

    /*
    public function getStats()
{
    $totalSales = Order::sum('total');  // Ventes totales
    $totalOrders = Order::count();      // Nombre total de commandes
    $bestSellingProducts = Product::with('orders')
                            ->select('name', DB::raw('COUNT(orders.product_id) as total_sold'))
                            ->groupBy('name')
                            ->orderBy('total_sold', 'desc')
                            ->take(5)
                            ->get();  // Top 5 des produits les plus vendus

    return response()->json([
        'total_sales' => $totalSales,
        'total_orders' => $totalOrders,
        'best_selling_products' => $bestSellingProducts
    ]);
}*/


//--

    public function totalSales()
    {
        $totalSales = Order::sum('total_amount');
        return response()->json(['total_sales' => $totalSales]);
    }

    public function salesByDay()
    {

        //by desc
        $salesByDay = Order::selectRaw('DATE(created_at) as date')
                        ->selectRaw('SUM(total_amount) as daily_sales')
                        ->groupBy('date')
                        ->orderBy('date', 'desc')
                        ->get();

        //by asc

        return response()->json($salesByDay);
    }

    public function salesByProduct()
    {
        $salesByProduct = Product::selectRaw('products.name as product_name')
                                 ->selectRaw('SUM(order_items.quantity) as total_quantity')
                                 ->selectRaw('SUM(order_items.quantity * order_items.unit_price) as total_sales')
                                 ->join('order_items', 'products.id', '=', 'order_items.product_id')
                                 ->groupBy('products.name')
                                 ->orderBy('total_sales', 'desc')
                                 ->get();
        return response()->json($salesByProduct);
    }

    public function salesByShop()
    {
        $salesByShop = Shop::selectRaw('shops.name as shop_name')
                           ->selectRaw('SUM(orders.total_amount) as total_sales')
                           ->join('orders', 'shops.id', '=', 'orders.shop_id')
                           ->groupBy('shops.name')
                           ->orderBy('total_sales', 'desc')
                           ->get();
        return response()->json($salesByShop);
    }

    public function anonymoususers()
    {
        //Session by Cart
        $anonymoususers = Cart::/*whereNull('user_id')->*/distinct()->count('device_id');
        return response()->json(['anonymous_users' => $anonymoususers]);
    }

    public function authenticatedusers()
    {
        $authenticatedusers = User::count();
        return response()->json(['authenticated_users' => $authenticatedusers]);
    }

    public function mostViewedProducts()
    {
        //ProductView by Product
        $mostViewedProducts = Product::selectRaw('products.name as product_name')
                                                    ->selectRaw('COUNT(product_views.id) as view_count')
                                                    ->join('products', 'product_views.product_id', '=', 'products.id')
                                                    ->groupBy('products.name')
                                                    ->orderBy('view_count', 'desc')
                                                    ->get();
        return response()->json($mostViewedProducts);
    }

    public function cartConversionRate()
    {
        $conversionRate = (Order::where('checked_out', 1)->count() / \App\Models\Cart::count()) * 100;
        return response()->json(['conversion_rate' => $conversionRate]);
    }

    public function totalOrders()
    {
        $totalOrders = Order::count();
        return response()->json(['total_orders' => $totalOrders]);
    }

    public function ordersByuser()
    {
        $ordersByuser = \App\Models\user::selectRaw('users.name as user_name')
                                        ->selectRaw('COUNT(orders.id) as order_count')
                                        ->join('orders', 'users.id', '=', 'orders.user_id')
                                        ->groupBy('users.name')
                                        ->orderBy('order_count', 'desc')
                                        ->get();
        return response()->json($ordersByuser);
    }


//--

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

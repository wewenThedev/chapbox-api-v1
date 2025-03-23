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
    public function latestOrders(){
        $latestOrders = Order::with(['user', 'brand', 'comments'])
            ->latest()
            ->paginate(10);
    }

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


    public function getTotalOrders()
    {
        $totalOrders = Order::count();
        return response()->json($totalOrders, 201);
    }

    public function getTotalHtSales()
    {
        $totalSales = Order::sum('total_ht');  // Ventes totales
        return response()->json($totalSales, 201);
    }

    public function getTotalTtcSales()
    {
        $totalSales = Order::sum('total_ttc');  // Ventes totales
        return response()->json($totalSales, 201);
    }

    public function getBestSellingProducts()
    {
        $bestSellingProducts = Product::with('orders')
            ->select('name', DB::raw('COUNT(orders.product_id) as total_sold'))
            ->groupBy('name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();  // Top 5 des produits les plus vendus

        return response()->json($bestSellingProducts, 201);
    }

    public function getTotalHtSalesByShop(string $shopId)
    {
        if (auth()->check() && auth()->user()->profile === 2) {
            $managerId = auth()->id();
            $totalSalesByShop = Order::where('manager_id', $managerId)->sum('total_ht');
        } else {
            //$totalSalesByShop = Order::sum('total_ht')->groupBy();
            $totalSalesByShop = OrderItem::sum('total_ht')->groupBy('shop');
        }

        return response()->json($totalSalesByShop, 201);
    }
    public function getTotalTtcSalesByShop(string $shopId)
    {
        $managerId = auth()->id();
        $totalSalesByShop = Order::where('manager_id', $managerId)->sum('total_ttc');
        return response()->json($totalSalesByShop, 201);
    }

    public function getTotalOrdersByShop()
    {
        $managerId = auth()->id();
        //if auth()->user()->profile_id == 1 -- do{}
        $totalOrdersByShop = Order::where('manager_id', $managerId)->count();
        return response()->json($totalOrdersByShop, 201);
    }

    public function getBestSellingProductsByShop()
    {
        $managerId = auth()->id();
        $shopId = Shop::where('manager_id', $managerId)->get('id');

        $bestSellingProductsByShop = Product::where('shop_id', $shopId)
            ->with(['shops', 'orders'])
            ->select('name', DB::raw('COUNT(orders.product_id) as total_sold'))
            ->groupBy('name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();  // Top 5 des produits les plus vendus

        return response()->json($bestSellingProductsByShop, 201);
    }

    public function getGlobalKpi()
    {

        $totalVisitors = Cart::count();  // Nombre total de visiteurs
        $totalOrders = Order::count();      // Nombre total de commandes
        $conversionRate = $totalOrders / $totalVisitors * 100;  // Taux de conversion

        $averageOrderValue = Order::avg('total');  // Valeur moyenne des commandes
        $averageProcessingTime = Order::where('status', 'completed')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_time')
            ->first()->avg_time;  // Temps moyen de traitement d’une commande

        return response()->json([
            'conversion_rate' => $conversionRate,
            'average_order_value' => $averageOrderValue,
            'average_processing_time' => $averageProcessingTime
        ], 201);
    }

}
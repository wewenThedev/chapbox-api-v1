<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;

use App\Models\Supermarket;
use App\Models\User;
use App\Models\Order;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ShopProduct;
use App\Models\Cart;
use App\Models\ShoppingDetails;



class OrderController extends Controller
{

    public function updateStatus(string $id){

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (auth()->user()->role == 'manager') {
            $orders = Order::where('Order_id', auth()->user()->Order_id)->get();
        } else {
            $orders = Order::all();
        }
    
        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        //
        $requestValidated = $request->validated();

        $order = Order::create($requestValidated);

        //return response()->json(['message' => 'Order placed successfully'], 201);
        return response()->json(['message' => 'Order placed successfully']);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $order = Order::findOrFail($id);
        return response()->json($order, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, string $id)
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
    public function destroy(string $id)
    {
        //
        $order = Order::findOrFail($id);

    $this->authorize('delete', $order);

    $order->delete();

    //return response()->json(['message' => 'Order deleted successfully'], 204);
    return response()->json(null, 204);
    }

    public function updateOrderStatus(){
        //to write
    }

    //spublic function 



    //admin functions

    public function getTotalOrders(){
        $totalOrders = Order::count();
        return response()->json($totalOrders, 201); 
    }

    public function getTotalHtSales(){
        $totalSales = Order::sum('total_ht');  // Ventes totales
        return response()->json($totalSales, 201);

    }

    public function getTotalTtcSales(){
        $totalSales = Order::sum('total_ttc');  // Ventes totales
        return response()->json($totalSales, 201);

    }

    public function getBestSellingProducts(){
        $bestSellingProducts = Product::with('orders')
                            ->select('name', DB::raw('COUNT(orders.product_id) as total_sold'))
                            ->groupBy('name')
                            ->orderBy('total_sold', 'desc')
                            ->take(5)
                            ->get();  // Top 5 des produits les plus vendus

    return response()->json($bestSellingProducts, 201);
    }

    public function getTotalHtSalesByShop(string $shopId){
        $managerId = auth()->id();
        $totalSalesByShop = Order::where('manager_id', $managerId)->sum('total_ht');
        return response()->json($totalSalesByShop, 201);

    }
    public function getTotalTtcSalesByShop(string $shopId){
        $managerId = auth()->id();
        $totalSalesByShop = Order::where('manager_id', $managerId)->sum('total_ttc');
        return response()->json($totalSalesByShop, 201);

    }

    public function getTotalOrdersByShop(){
        $managerId = auth()->id();
        $totalOrdersByShop = Order::where('manager_id', $managerId)->count();
        return response()->json($totalOrdersByShop, 201); 
    }

    public function getBestSellingProductsByShop(){
        $managerId = auth()->id();
        $shopId = Shop::where('manager_id', $managerId)->get('id');

        $bestSellingProductsByShop = Product::where('shop_id', $shopId)
                            ->with(['shops','orders'])
                            ->select('name', DB::raw('COUNT(orders.product_id) as total_sold'))
                            ->groupBy('name')
                            ->orderBy('total_sold', 'desc')
                            ->take(5)
                            ->get();  // Top 5 des produits les plus vendus

    return response()->json($bestSellingProductsByShop, 201);
    }

    public function getGlobalKpi(){

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

    /*To DO After
    public function getKpiByShop(){

    }*/


}

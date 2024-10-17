<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagerDashboardController extends Controller
{
/*
    $managerId = auth()->id();
    $totalSales = Order::where('manager_id', $managerId)->sum('total');
    $totalOrders = Order::where('manager_id', $managerId)->count();
    
    $bestSellingProducts = Product::where('manager_id', $managerId)
                            ->with('orders')
                            ->select('name', DB::raw('COUNT(orders.product_id) as total_sold'))
                            ->groupBy('name')
                            ->orderBy('total_sold', 'desc')
                            ->take(5)
                            ->get();

    return response()->json([
        'total_sales' => $totalSales,
        'total_orders' => $totalOrders,
        'best_selling_products' => $bestSellingProducts
    ]);*/

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

<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary()
    {
        try {
            $totalCategories = Category::count(); // Count the total number of categories
            $totalProducts = Product::count(); // Count the total number of products
            $totalInvoices = Invoice::count(); // Count the total number of invoices
            $totalRevenue = Invoice::where('status','finalized')->sum('grand_total'); // Calculate the total revenue

            $stockAlerts = Product::with('category')
                ->whereColumn('stock_qty','<=','low_stock_threshold')
                ->where('low_stock_threshold','>',0)
                ->orderBy('stock_qty')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Dashboard summary fetched successfully',
                'data' => [
                    'total_categories' => $totalCategories,
                    'total_products' => $totalProducts,
                    'total_invoices' => $totalInvoices,
                    'total_revenue' => round((float)$totalRevenue, 2),
                    'stock_alerts' => $stockAlerts,
                ]
            ]);
        }catch (\Throwable $e){
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while fetching dashboard summary',
                ], 500);
        }
    }
}

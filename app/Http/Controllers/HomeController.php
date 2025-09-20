<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $days = 30;

        // Buat daftar tanggal terakhir 30 hari
        $dates = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->format('Y-m-d');
        }

        // Ambil total profit per hari
        $totals = Order::selectRaw("DATE(order_date) as date, SUM(order_amount) as total")
            ->whereNotNull('order_date')
            ->whereBetween('order_date', [now()->subDays($days - 1)->startOfDay(), now()->endOfDay()])
            ->groupBy(DB::raw('DATE(order_date)'))
            ->pluck('total', 'date')
            ->toArray();

        // Map ke array sesuai daftar tanggal
        $totalProfits = array_map(function ($d) use ($totals) {
            return isset($totals[$d]) ? (int)$totals[$d] : 0;
        }, $dates);

        // Hitung total summary
        $productCount = Product::count();
        $transactionCount = Order::count();
        $totalProfitSum = array_sum($totalProfits);

        // Data tambahan untuk dashboard
        $products = Product::latest()->limit(8)->get();
        $categories = Category::withCount('products')->get();
        $recentTransactions = Order::latest()->limit(8)->get();

        return view('kasir.dashboard', compact(
            'dates',
            'totalProfits',
            'productCount',
            'transactionCount',
            'totalProfitSum',
            'products',
            'categories',
            'recentTransactions'
        ));
    }
}

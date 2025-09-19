<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Prepare last 14 days labels
    $days = 30;
        $dates = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->format('Y-m-d');
        }

        // Fetch totals per date using order_amount if present, fallback to total_amount
        $totals = Transaction::selectRaw("DATE(order_date) as date, COALESCE(SUM(order_amount), SUM(total_amount)) as total")
            ->whereNotNull('order_date')
            ->whereBetween('order_date', [now()->subDays($days - 1)->startOfDay(), now()->endOfDay()])
            ->groupBy(DB::raw('DATE(order_date)'))
            ->pluck('total', 'date')
            ->toArray();

        $totalProfits = array_map(function($d) use ($totals) {
            return isset($totals[$d]) ? (int)$totals[$d] : 0;
        }, $dates);

        $productCount = Product::count();
        $transactionCount = Transaction::count();
        $totalProfitSum = array_sum($totalProfits);

        // Additional data for dashboard lists
        $products = Product::latest()->limit(8)->get();
        $categories = Category::withCount('products')->get();
        $recentTransactions = Transaction::latest()->limit(8)->get();

        return view('admin.dashboard', compact('dates','totalProfits','productCount','transactionCount','totalProfitSum','products','categories','recentTransactions'));
    }
}

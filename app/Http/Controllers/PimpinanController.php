<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PimpinanController extends Controller
{
    /**
     * Halaman Dashboard Pimpinan
     */
    public function dashboard()
    {
        // Statistik dasar
        $productCount    = Product::count();
        $categoryCount   = Category::count();
        $transactionCount = Order::count();
        $totalRevenue    = Order::sum('total_amount');

        // Top 5 produk terlaris
        $topSelling = OrderDetail::select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Data untuk chart pendapatan bulanan (12 bulan terakhir)
        $monthlyRevenue = Order::select(
                DB::raw('DATE_FORMAT(order_date, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chartLabels = $monthlyRevenue->pluck('month');
        $chartData   = $monthlyRevenue->pluck('total');

        return view('dashboard.dashboard-pimpinan', compact(
            'productCount',
            'categoryCount',
            'transactionCount',
            'totalRevenue',
            'topSelling',
            'chartLabels',
            'chartData'
        ));
    }

    /**
     * Laporan Transaksi
     */
    public function laporan()
    {
        $orders = Order::latest('order_date')->get();
        return view('pimpinan.laporan', compact('orders'));
    }

    /**
     * Detail Laporan Transaksi
     */
    public function detailLaporan(string $id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('pimpinan.detail-laporan', compact('order'));
    }
}

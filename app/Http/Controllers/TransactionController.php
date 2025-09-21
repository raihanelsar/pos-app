<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', 1)->get();
        $orders = Order::with('items.product')->latest()->paginate(20);

        return view('kasir.index', compact('products', 'orders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|integer|min:0',
            'paid_amount' => 'required|integer|min:0',
        ]);

        // Hitung total
        $totalAmount = collect($data['items'])->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });

        // Simpan order
        $order = Order::create([
            'order_code'   => 'ORD' . now()->format('YmdHis') . rand(1000, 9999),
            'customer_name'=> $data['customer_name'],
            'order_date'   => now(),
            'total_amount' => $totalAmount,
            'order_change' => $data['paid_amount'] - $totalAmount,
        ]);

        // Simpan detail order
        foreach ($data['items'] as $item) {
            $order->items()->create([
                'product_id'   => $item['product_id'],
                'qty'          => $item['quantity'],
                'order_price'  => $item['unit_price'],
                'order_subtotal' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('kasir.index')
            ->with('success', 'Transaksi berhasil disimpan');
    }

    public function laporan()
    {
        $orders = Order::orderBy('order_date', 'desc')->get();
        return view('pimpinan.laporan', compact('orders'));
    }

    public function detailLaporan(string $id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('pimpinan.detail-laporan', compact('order'));
    }

    /**
     * Dashboard Kasir
     */
    public function dashboard()
    {
        // Jumlah produk aktif
        $productCount = Product::where('is_active', 1)->count();

        // Jumlah kategori (kalau ada relasi kategori di model Product)
        $categoriesCount = DB::table('categories')->count();

        // Jumlah transaksi
        $transactionCount = Order::count();

        // Total profit (pakai total_amount sebagai profit sederhana)
        $totalProfitSum = Order::sum('total_amount');

        // Ambil data untuk grafik
        $profits = Order::select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dates = $profits->pluck('date');
        $totalProfits = $profits->pluck('total');

        // Transaksi terbaru
        $recentTransactions = Order::latest()->take(10)->get()->map(function ($order) {
            return (object) [
                'transaction_code' => $order->order_code,
                'formatted_date'   => $order->order_date?->format('d/m/Y H:i'),
                'total'            => $order->total_amount,
                'cashier_name'     => $order->cashier->name ?? '-', // kalau ada relasi cashier
            ];
        });

    }
}

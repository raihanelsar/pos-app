<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Halaman utama kasir
     * Sekarang juga berfungsi sebagai dashboard.
     */
    public function dashboard()
    {
        // Ambil produk aktif yang stoknya masih ada
        $products = Product::where('is_active', 1)
            ->where('product_qty', '>', 0)
            ->get();

        // Ambil riwayat transaksi terbaru beserta detail & produk
        $orders = Order::with('items.product')
            ->latest()
            ->paginate(20);

        // Ambil data dashboard
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $todayOrders = Order::whereBetween('created_at', [$todayStart, $todayEnd])->count();
        $todaySales = Order::whereBetween('created_at', [$todayStart, $todayEnd])->sum('order_amount');
        $activeProducts = Product::where('is_active', true)->count();
        $topProducts = OrderDetail::selectRaw('product_id, SUM(qty) as sold_qty')
            ->groupBy('product_id')
            ->orderByDesc('sold_qty')
            ->with('product')
            ->take(5)
            ->get();

        // Kirim semua variabel ke tampilan
        return view('dashboard.dashboard-kasir', compact('products', 'orders', 'todayOrders', 'todaySales', 'activeProducts', 'topProducts'));
    }

    /**
     * Simpan transaksi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string',
            'items'       => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $total = collect($request->items)->sum(function($i){
                return $i['quantity'] * $i['unit_price'];
            });

            // Hitung kembalian
            $change = $request->paid_amount - $total;

            // Periksa apakah jumlah bayar mencukupi
            if ($change < 0) {
                // Jika pembayaran tidak mencukupi, rollback dan kembalikan error
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah bayar tidak mencukupi.',
                ], 422);
            }

            $order = Order::create([
                'order_code'   => 'TRX' . now()->format('YmdHis'),
                'order_date'   => now(),
                'order_amount' => $total,
                'paid_amount'  => $request->paid_amount,
                'order_change' => $change, // Simpan kembalian yang dihitung
                'customer_name'=> $request->customer_name,
                'user_id'      => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                // Periksa apakah stok produk mencukupi sebelum membuat
                $product = Product::find($item['product_id']);
                if ($product->product_qty < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok produk tidak mencukupi.',
                    ], 422);
                }

                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal'   => $item['quantity'] * $item['unit_price'],
                ]);

                Product::where('id', $item['product_id'])
                    ->decrement('product_qty', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'print_url' => route('kasir.print', $order->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // New Function based on index.blade.php
    public function index()
    {
        // Ambil produk aktif yang stoknya masih ada
        $products = Product::where('is_active', 1)
            ->where('product_qty', '>', 0)
            ->get();

        // Ambil riwayat transaksi terbaru
        $orders = Order::with('items')
            ->latest()
            ->paginate(20);

        return view('kasir.index', compact('products', 'orders'));
    }
}

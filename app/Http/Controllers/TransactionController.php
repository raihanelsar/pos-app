<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

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
            'payment_amount' => 'required|integer|min:0',
        ]);

        $total = collect($data['items'])->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });

        $payment = $data['payment_amount'];

        if ($payment < $total) {
            return response()->json([
                'message' => 'Pembayaran kurang dari total transaksi',
                'errors' => ['payment_amount' => ['Pembayaran tidak mencukupi jumlah total']]]
            , 422);
        }

        $order = Order::create([
            'customer_name' => $data['customer_name'] ?? null,
            'order_code' => 'ORD' . now()->format('YmdHis') . rand(100, 999),
            'order_date' => now(),
            'order_amount' => $total,
            'total_amount' => $total,
            'order_change' => $payment - $total,
            'order_status' => 'paid',
        ]);

        foreach ($data['items'] as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'qty' => $item['quantity'],
                'order_price' => $item['unit_price'],
                'order_subtotal' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return response()->json([
            'message' => 'Transaksi berhasil disimpan',
            'transaction_id' => $order->id,
        ]);
    }
}

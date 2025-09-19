<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active',1)->get();
        $transactions = Transaction::with('items.product')->latest()->paginate(20);
        return view('transactions.index', compact('products','transactions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|integer|min:0',
            'payment_amount' => 'required|integer|min:0'
        ]);

        $total = 0;
        foreach ($data['items'] as $it) {
            $total += ($it['quantity'] * $it['unit_price']);
        }

        // order fields
        $orderCode = 'ORD' . now()->format('YmdHis') . rand(100,999);
        $orderDate = now();
        $orderAmount = $total;
        $payment = intval($data['payment_amount']);

        // Server-side guard: reject if payment is insufficient
        if ($payment < $orderAmount) {
            return response()->json([
                'message' => 'Pembayaran kurang dari total transaksi',
                'errors' => ['payment_amount' => ['Pembayaran tidak mencukupi jumlah total']]
            ], 422);
        }

        $orderChange = max(0, $payment - $orderAmount);
        $orderStatus = 'paid';

        $tx = Transaction::create([
            'user_id' => optional(auth())->id(),
            'customer_name' => $data['customer_name'] ?? null,
            'total_amount' => $total,
            'order_code' => $orderCode,
            'order_date' => $orderDate,
            'order_amount' => $orderAmount,
            'order_change' => $orderChange,
            'order_status' => $orderStatus,
        ]);

        foreach ($data['items'] as $it) {
            TransactionItem::create([
                'transaction_id' => $tx->id,
                'product_id' => $it['product_id'],
                'quantity' => $it['quantity'],
                'unit_price' => $it['unit_price'],
                'line_total' => $it['quantity'] * $it['unit_price']
            ]);

            // Also store in order_details table per request
            \App\Models\OrderDetail::create([
                'order_id' => $tx->id,
                'product_id' => $it['product_id'],
                'qty' => $it['quantity'],
                'order_price' => $it['unit_price'],
                'order_subtotal' => $it['quantity'] * $it['unit_price']
            ]);
        }

        return response()->json(['message' => 'Transaksi berhasil disimpan', 'transaction_id' => $tx->id]);
    }
}

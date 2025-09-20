<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PimpinanController extends Controller
{
    public function index()
    {
        // Ambil data transaksi dari database
        $orders = Order::with('items') // kalau ada relasi items
            ->orderBy('order_date', 'desc')
            ->get()
            ->map(function ($order) {
                return (object) [
                    'id' => $order->id,
                    'order_code' => $order->order_code,
                    'formatted_amount' => 'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                    'order_date' => $order->order_date,
                    'formatted_change' => 'Rp ' . number_format($order->change_amount, 0, ',', '.'),
                ];
            });

        return view('pimpinan.laporan', compact('orders'));
    }

    public function detail($id)
    {
        $order = Order::with('items')->findOrFail($id);

        return view('pimpinan.detail-laporan', compact('order'));
    }
}

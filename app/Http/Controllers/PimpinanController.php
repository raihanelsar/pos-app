<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PimpinanController extends Controller
{
    public function index()
    {
        // Ambil data transaksi terbaru
        $orders = Order::with('items')
            ->latest('order_date')
            ->get()
            ->map(function ($order) {
                return (object) [
                    'id' => $order->id,
                    'order_code' => $order->order_code,
                    'formatted_amount' => $this->formatRupiah($order->total_amount),
                    'order_date' => $order->order_date,
                    'formatted_change' => $this->formatRupiah($order->change_amount),
                ];
            });

        return view('pimpinan.laporan', compact('orders'));
    }

    public function detail($id)
    {
        $order = Order::with('items')->findOrFail($id);

        return view('pimpinan.detail-laporan', compact('order'));
    }

    /**
     * Format angka ke Rupiah
     */
    private function formatRupiah($value)
    {
        return 'Rp ' . number_format($value ?? 0, 0, ',', '.');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'order_price',
        'order_subtotal',
    ];

    protected $appends = ['formatted_price', 'formatted_subtotal'];

    // 🔹 Format harga per item
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->order_price ?? 0, 0, ',', '.');
    }

    // 🔹 Format subtotal (harga x qty)
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->order_subtotal ?? 0, 0, ',', '.');
    }

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relasi ke order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

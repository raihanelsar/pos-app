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
        'order_subtotal'
    ];

    protected $appends = ['formatted_price', 'formatted_subtotal'];

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp. ' . number_format($this->order_price, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp. ' . number_format($this->order_subtotal, 0, ',', '.');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

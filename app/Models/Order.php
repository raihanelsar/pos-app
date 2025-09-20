<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'order_code',
        'order_date',
        'total_amount',   // gunakan ini saja, jangan duplikat dengan order_amount
        'order_change',
        'order_status',
    ];

    protected $appends = ['formatted_date', 'formatted_total', 'formatted_change'];

    // 🔹 Format tanggal
    public function getFormattedDateAttribute()
    {
        return date('d-m-Y H:i', strtotime($this->order_date));
    }

    // 🔹 Format total
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_amount ?? 0, 0, ',', '.');
    }

    // 🔹 Format kembalian
    public function getFormattedChangeAttribute()
    {
        return 'Rp ' . number_format($this->order_change ?? 0, 0, ',', '.');
    }

    // Relasi ke detail item
    public function items()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'customer_name',
        'order_date',
        'order_amount',
        'total_amount',
        'order_change',
        'order_status',
    ];

    // 🔹 Cast otomatis ke Carbon
    protected $casts = [
        'order_date' => 'datetime',
    ];

    // 🔹 Accessor tambahan
    protected $appends = ['formatted_date', 'formatted_total', 'formatted_change'];

    public function getFormattedDateAttribute(): ?string
    {
        return $this->order_date
            ? $this->order_date->format('d-m-Y H:i')
            : null;
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount ?? 0, 0, ',', '.');
    }

    public function getFormattedChangeAttribute(): string
    {
        return 'Rp ' . number_format($this->order_change ?? 0, 0, ',', '.');
    }

    // 🔹 Relasi ke detail item
    public function items()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}

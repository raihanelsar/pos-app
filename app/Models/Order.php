<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_code',
        'order_date',
        'order_amount',
        'order_change',
        'order_status'
    ];

    protected $appends = ['formatted_date', 'formatted_amount', 'formatted_change'];

    public function getFormattedDateAttribute()
    {
        return date('d-m-Y', strtotime($this->order_date));
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp.' . number_format($this->order_amount, 2, ',', '.');
    }

    public function getFormattedChangeAttribute()
    {
        return 'Rp.' . number_format($this->order_change, 2, ',', '.');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}

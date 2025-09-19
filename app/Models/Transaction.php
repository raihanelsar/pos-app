<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'customer_name',
        'total_amount',
        'order_code',
        'order_date',
        'order_amount',
        'order_change',
        'order_status'
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}

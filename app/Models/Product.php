<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'product_name',
        'product_photo',
        'product_price',
        'product_description',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

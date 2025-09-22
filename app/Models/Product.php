<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'product_name',
        'product_photo',
        'product_qty',
        'product_price',
        'product_description',
        'is_active'
    ];

    protected $appends = ['category_name', 'formatted_price'];

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp. ' . number_format($this->product_price, 0, ',', '.');
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->category->category_name;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepurchaseProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
        'pv_value',
        'stock',
        'image',
        'description',
        'is_active',
        'sku'
    ];

    protected $casts = [
        'price' => 'float',
        'pv_value' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-product.jpg');
    }

    // Check if product is in stock
    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }
}
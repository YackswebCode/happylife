<?php
// app/Models/RepurchaseProduct.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepurchaseProduct extends Model
{
    use HasFactory;

    protected $table = 'repurchase_products';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'old_price',
        'pv_value',
        'stock',
        'image',
        'gallery',
        'is_active',
    ];

    protected $casts = [
        'price'      => 'float',
        'old_price'  => 'float',
        'pv_value'   => 'integer',
        'stock'      => 'integer',
        'gallery'    => 'array',
        'is_active'  => 'boolean',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
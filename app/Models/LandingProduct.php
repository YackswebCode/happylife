<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingProduct extends Model
{
    use HasFactory;

    protected $table = 'landing_products';

    protected $fillable = [
        'name',
        'image',
        'description',
        'display_price',
        'category',
        'order',
        'is_active'
    ];

    protected $casts = [
        'display_price' => 'float',
        'order' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Accessor: Get full image URL with fallback
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        return asset('images/default-product.jpg');
    }
}
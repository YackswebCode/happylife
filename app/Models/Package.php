<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'pv',
        'product_entitlement',
        'direct_bonus_amount',
        'pairing_cap',
        'description',
        'is_active',
        'order'
    ];

    protected $casts = [
        'price' => 'float',
        'pv' => 'integer',
        'direct_bonus_amount' => 'float',
        'pairing_cap' => 'float',
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'package_products');
    }

    public function upgrades()
    {
        return $this->hasMany(Upgrade::class, 'new_package_id');
    }






}
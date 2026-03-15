<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'package_id',
        'price',
        'pv',
        'image'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function claims()
    {
        return $this->hasMany(ProductClaim::class);
    }
}
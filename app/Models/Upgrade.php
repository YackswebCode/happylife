<?php
// app/Models/Upgrade.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upgrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'old_package_id',
        'new_package_id',
        'difference_amount',
        'payment_method',
        'status',
        'reference',
    ];

    protected $casts = [
        'difference_amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function oldPackage()
    {
        return $this->belongsTo(Package::class, 'old_package_id');
    }

    public function newPackage()
    {
        return $this->belongsTo(Package::class, 'new_package_id');
    }
}
<?php
// app/Models/ProductClaim.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'pickup_center_id',
        'claim_number',
        'status',
        'admin_notes',
        'claimed_at',
        'approved_at',
        'collected_at',
        'receipt_data',
    ];

    protected $casts = [
        'claimed_at'   => 'datetime',
        'approved_at'  => 'datetime',
        'collected_at' => 'datetime',
        'receipt_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function pickupCenter()
    {
        return $this->belongsTo(PickupCenter::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCollected($query)
    {
        return $query->where('status', 'collected');
    }
}
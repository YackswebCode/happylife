<?php
// app/Models/FundingRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'transaction_id',
        'proof',
        'notes',
        'status',
        'admin_notes',
        'approved_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
<?php
// app/Models/Withdrawal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'fee',
        'net_amount',
        'status',
        'bank_details',
        'admin_notes',
        'processed_at',
        'reference',
    ];

    protected $casts = [
        'amount'      => 'float',
        'fee'         => 'float',
        'net_amount'  => 'float',
        'bank_details' => 'array',
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
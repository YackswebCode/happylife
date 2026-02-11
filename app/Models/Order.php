<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'total',
        'pv_total',
        'status',
        'payment_status',
        'payment_method',
        'items',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'total'    => 'float',
        'pv_total' => 'integer',
        'items'    => 'array',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
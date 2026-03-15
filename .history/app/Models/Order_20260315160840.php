<?php

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
        'state_id',
        'pickup_center_id',
        'state_name',
        'pickup_center_name',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'total'    => 'float',
        'pv_total' => 'integer',
        'items'    => 'array',
        'state_id' => 'integer',
        'pickup_center_id' => 'integer',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the state associated with the order.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the pickup centre associated with the order.
     */
    public function pickupCenter()
    {
        return $this->belongsTo(PickupCenter::class);
    }
}
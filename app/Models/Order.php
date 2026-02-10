<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_amount',
        'pv_value',
        'status',
        'payment_method',
        'payment_status',
        'shipping_address',
        'tracking_number',
        'order_date'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'total_amount' => 'float',
        'pv_value' => 'integer',
        'order_date' => 'datetime'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(RepurchaseProduct::class, 'product_id');
    }

    // Accessor for formatted total amount
    public function getFormattedTotalAttribute()
    {
        return '₦' . number_format($this->total_amount, 2);
    }
}
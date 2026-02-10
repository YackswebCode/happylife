<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'user_id',
        'type',
        'amount',
        'description',
        'reference',
        'status',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'float',
        'metadata' => 'array'
    ];

    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';
    const TYPE_WITHDRAWAL = 'withdrawal';
    const TYPE_TRANSFER = 'transfer';
    const TYPE_VTU = 'vtu';

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for formatted amount
    public function getFormattedAmountAttribute()
    {
        $sign = $this->type === self::TYPE_CREDIT ? '+' : '-';
        return $sign . '₦' . number_format($this->amount, 2);
    }
}
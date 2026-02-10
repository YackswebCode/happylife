<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'balance',
        'locked_balance'
    ];

    protected $casts = [
        'balance' => 'float',
        'locked_balance' => 'float'
    ];

    const TYPE_COMMISSION = 'commission';
    const TYPE_REGISTRATION = 'registration';
    const TYPE_RANK = 'rank';
    const TYPE_SHOPPING = 'shopping';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    // Methods
    public function getAvailableBalanceAttribute()
    {
        return $this->balance - $this->locked_balance;
    }

    public function canWithdraw($amount)
    {
        return $this->available_balance >= $amount;
    }
}
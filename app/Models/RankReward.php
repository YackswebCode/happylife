<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rank_id',
        'reward_type',
        'amount',
        'description',
        'status',
        'awarded_at'
    ];

    protected $casts = [
        'amount' => 'float',
        'awarded_at' => 'datetime'
    ];

    const TYPE_CASH = 'cash';
    const TYPE_PRODUCT = 'product';
    const TYPE_TRIP = 'trip';
    const TYPE_CAR = 'car';
    const TYPE_HOUSE = 'house';

    const STATUS_PENDING = 'pending';
    const STATUS_AWARDED = 'awarded';
    const STATUS_DISPATCHED = 'dispatched';
    const STATUS_RECEIVED = 'received';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }
}
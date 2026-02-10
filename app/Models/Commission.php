<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'from_user_id',
        'from_package_id',
        'pv',
        'status',
        'wallet_type'
    ];

    protected $casts = [
        'amount' => 'float',
        'pv' => 'integer'
    ];

    const TYPE_DIRECT = 'direct';
    const TYPE_PAIRING = 'pairing';
    const TYPE_UPGRADE = 'upgrade';
    const TYPE_INDIRECT = 'indirect';
    const TYPE_SHOPPING = 'shopping';
    const TYPE_RANK = 'rank';

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function fromPackage()
    {
        return $this->belongsTo(Package::class, 'from_package_id');
    }
}
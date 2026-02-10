<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upgrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'old_package_id',
        'new_package_id',
        'upgrade_amount',
        'status',
        'payment_method',
        'payment_status',
        'upgrade_date',
        'bonus_paid'
    ];

    protected $casts = [
        'upgrade_amount' => 'float',
        'bonus_paid' => 'boolean',
        'upgrade_date' => 'datetime'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function oldPackage()
    {
        return $this->belongsTo(Package::class, 'old_package_id');
    }

    public function newPackage()
    {
        return $this->belongsTo(Package::class, 'new_package_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'email_verified_at', 'username', 'phone', 'password',
        'sponsor_id', 'placement_id', 'placement_position', 'package_id',
        'country', 'state', 'product_id', 'pickup_center_id', 'rank_id',
        'left_count', 'right_count', 'total_pv', 'current_pv', 'status',
        'registration_date', 'role', 'remember_token', 'verification_code',
        'referral_code', 'payment_status', 'activated_at',
        'commission_wallet_balance', 'registration_wallet_balance',
        'rank_wallet_balance', 'shopping_wallet_balance'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activated_at' => 'datetime',
    ];

    // Relationships
    
    /**
     * Get the user's direct downlines (users they sponsored)
     */
    public function directDownlines()
    {
        return $this->hasMany(User::class, 'sponsor_id');
    }

    /**
     * Get the user's sponsor (upline who referred them)
     */
    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    /**
     * Get the user's placement (binary tree parent)
     */
    public function placement()
    {
        return $this->belongsTo(User::class, 'placement_id');
    }

    /**
     * Get the user's left leg downlines (in binary tree)
     */
    public function leftDownlines()
    {
        return $this->hasMany(User::class, 'placement_id')
            ->where('placement_position', 'left');
    }

    /**
     * Get the user's right leg downlines (in binary tree)
     */
    public function rightDownlines()
    {
        return $this->hasMany(User::class, 'placement_id')
            ->where('placement_position', 'right');
    }

    /**
     * Get the user's package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the user's product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user's commissions
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Get the user's wallet transactions
     */
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get the user's payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the user's rank
     */
    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    // Helper methods
    
    /**
     * Get total downlines count (recursive)
     */
    public function getTotalDownlinesCountAttribute()
    {
        return $this->getTotalDownlinesRecursive($this);
    }

    private function getTotalDownlinesRecursive($user)
    {
        $count = $user->directDownlines->count();
        foreach ($user->directDownlines as $downline) {
            $count += $this->getTotalDownlinesRecursive($downline);
        }
        return $count;
    }

    /**
     * Check if user is eligible for dashboard access
     */
    public function canAccessDashboard()
    {
        return $this->email_verified_at && 
               $this->payment_status === 'paid' && 
               $this->status === 'active';
    }

    /**
 * Get the KYC record associated with the user.
 * Typically one-to-one, but we may keep multiple submissions; use latest.
 */
public function kyc()
{
    return $this->hasOne(Kyc::class)->latestOfMany();
}

/**
 * Get all KYC submissions for the user.
 */
public function kycs()
{
    return $this->hasMany(Kyc::class);
}
}
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

    // Binary tree children
    public function leftChild()
    {
        return $this->hasOne(User::class, 'placement_id', 'id')
                    ->where('placement_position', 'left');
    }

    public function rightChild()
    {
        return $this->hasOne(User::class, 'placement_id', 'id')
                    ->where('placement_position', 'right');
    }

    public function leftChildren()
    {
        return $this->hasMany(User::class, 'placement_id', 'id')
                    ->where('placement_position', 'left')
                    ->with(['package', 'rank']);
    }

    public function rightChildren()
    {
        return $this->hasMany(User::class, 'placement_id', 'id')
                    ->where('placement_position', 'right')
                    ->with(['package', 'rank']);
    }

    public function children()
    {
        return $this->hasMany(User::class, 'placement_id', 'id')
                    ->with(['package', 'rank']);
    }

    // Wallet relationships and accessors
    
    /**
     * Get all wallets belonging to the user.
     */
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * Get the shopping wallet for the user.
     */
    public function shoppingWallet()
    {
        return $this->hasOne(Wallet::class)->where('type', 'shopping');
    }

    /**
     * Get the balance of a specific wallet type.
     * Does NOT auto‑create the wallet – returns 0 if not found.
     *
     * @param string $type
     * @return float
     */
    public function getWalletBalance($type)
    {
        $wallet = $this->wallets()->where('type', $type)->first();
        return $wallet ? $wallet->balance : 0;
    }

    /**
     * Get the commission wallet balance.
     */
    public function getCommissionWalletAttribute()
    {
        return $this->getWalletBalance('commission');
    }

    /**
     * Get the registration wallet balance.
     */
    public function getRegistrationWalletAttribute()
    {
        return $this->getWalletBalance('registration');
    }

    /**
     * Get the rank wallet balance.
     */
    public function getRankWalletAttribute()
    {
        return $this->getWalletBalance('rank');
    }

    /**
     * Get the shopping wallet balance.
     * AUTO‑CREATES the shopping wallet if it does not exist.
     *
     * @return float
     */
    public function getShoppingWalletBalanceAttribute()
    {
        $wallet = $this->shoppingWallet()->first();
        if (!$wallet) {
            $wallet = $this->shoppingWallet()->create([
                'type'           => 'shopping',
                'balance'        => 0,
                'locked_balance' => 0,
            ]);
        }
        return $wallet->balance ?? 0;
    }

    // Order & Product Claims
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function productClaims()
    {
        return $this->hasMany(ProductClaim::class);
    }

    public function hasPendingClaim()
    {
        return $this->productClaims()->where('status', 'pending')->exists();
    }

    public function hasApprovedClaim()
    {
        return $this->productClaims()->where('status', 'approved')->exists();
    }

    public function hasCollectedClaim()
    {
        return $this->productClaims()->where('status', 'collected')->exists();
    }

    // Package Upgrades
    
    public function upgrades()
    {
        return $this->hasMany(Upgrade::class);
    }

    public function getUpgradeablePackagesAttribute()
    {
        if (!$this->package) {
            return collect();
        }
        return Package::where('order', '>', $this->package->order)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
}
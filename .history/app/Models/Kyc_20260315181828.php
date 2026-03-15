<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    protected $table = 'kyc';

    protected $fillable = [
        'user_id',
        'document_type',
        'front_image',
        'back_image',
        'selfie_image',
        'id_number',
        'issue_date',
        'expiry_date',
        'place_of_issue',
        'status',
        'admin_comment',
        'submitted_at',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'issue_date'   => 'date',
        'expiry_date'  => 'date',
        'submitted_at' => 'datetime',
        'verified_at'  => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    /**
     * Relationship: KYC belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: KYC verified by Admin
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope for pending KYC
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved KYC
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected KYC
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'document_front',
        'document_back',
        'selfie_image',
        'status',
        'verified_at',
        'verified_by',
        'rejection_reason'
    ];

    const DOCUMENT_NIN = 'nin';
    const DOCUMENT_DRIVERS_LICENSE = 'drivers_license';
    const DOCUMENT_PASSPORT = 'passport';
    const DOCUMENT_VOTERS_CARD = 'voters_card';

    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Accessor for document type name
    public function getDocumentTypeNameAttribute()
    {
        return [
            self::DOCUMENT_NIN => 'National ID',
            self::DOCUMENT_DRIVERS_LICENSE => "Driver's License",
            self::DOCUMENT_PASSPORT => 'International Passport',
            self::DOCUMENT_VOTERS_CARD => "Voter's Card"
        ][$this->document_type] ?? 'Unknown';
    }
}
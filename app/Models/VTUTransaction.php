<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VtuTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider_id',
        'plan_id',
        'service_type',
        'phone_number',
        'amount',
        'commission',
        'status',
        'reference',
        'provider_reference',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'float',
        'commission' => 'float',
        'metadata' => 'array'
    ];

    const SERVICE_AIRTIME = 'airtime';
    const SERVICE_DATA = 'data';
    const SERVICE_CABLE = 'cable';
    const SERVICE_ELECTRICITY = 'electricity';

    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESSFUL = 'successful';
    const STATUS_FAILED = 'failed';
    const STATUS_PROCESSING = 'processing';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(VtuProvider::class);
    }

    public function plan()
    {
        return $this->belongsTo(VtuPlan::class);
    }

    // Accessor for service name
    public function getServiceNameAttribute()
    {
        return [
            self::SERVICE_AIRTIME => 'Airtime',
            self::SERVICE_DATA => 'Data',
            self::SERVICE_CABLE => 'Cable TV',
            self::SERVICE_ELECTRICITY => 'Electricity'
        ][$this->service_type] ?? 'Unknown';
    }
}
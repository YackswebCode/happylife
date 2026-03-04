<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VtuTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'service_type', 'provider_id', 'plan_id',
        'phone', 'smart_card', 'meter_number', 'amount',
        'status', 'reference', 'description'
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(VtuProvider::class, 'provider_id');
    }

    public function plan()
    {
        return $this->belongsTo(VtuPlan::class, 'plan_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VtuPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'name',
        'code',
        'service_type',
        'amount',
        'validity',
        'description',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'float',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function provider()
    {
        return $this->belongsTo(VtuProvider::class);
    }

    public function transactions()
    {
        return $this->hasMany(VtuTransaction::class);
    }
}
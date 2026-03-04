<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VtuPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'api_code',       // <-- add this
        'name',
        'amount',
        'validity',
        'size',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function provider()
    {
        return $this->belongsTo(VtuProvider::class, 'provider_id');
    }
}
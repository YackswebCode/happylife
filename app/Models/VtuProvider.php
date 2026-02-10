<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VtuProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'service_type',
        'logo',
        'api_endpoint',
        'api_key',
        'api_secret',
        'status',
        'commission_rate'
    ];

    protected $casts = [
        'commission_rate' => 'float',
        'status' => 'boolean'
    ];

    // Relationships
    public function plans()
    {
        return $this->hasMany(VtuPlan::class);
    }

    public function transactions()
    {
        return $this->hasMany(VtuTransaction::class);
    }
}
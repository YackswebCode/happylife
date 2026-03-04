<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VtuProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_code',   // <-- add this
        'category',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function plans()
    {
        return $this->hasMany(VtuPlan::class, 'provider_id');
    }
}
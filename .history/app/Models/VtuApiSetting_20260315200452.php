<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VtuApiSetting extends Model
{
    protected $table = 'vtu_api_settings';

    protected $fillable = [
        'name',
        'base_url',
        'api_key',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
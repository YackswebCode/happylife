<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VtuApiSetting extends Model
{
    protected $fillable = [
        'name', 'base_url', 'api_key', 'is_active'
    ];
}
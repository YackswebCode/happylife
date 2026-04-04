<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    protected $casts = [
        'value' => 'array',      // auto-encodes to JSON on save, decodes to array on retrieval
        'type' => 'string',
    ];
}
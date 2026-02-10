<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'payment_method',
        'reference',
        'gateway_reference',
        'status',
        'proof_url',
        'authorization_url',
        'gateway_response',
        'description',
    ];

    protected $casts = [
        'gateway_response' => 'array', // So you can save JSON directly
    ];
}

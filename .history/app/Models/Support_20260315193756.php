<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $table = 'supports';

    protected $fillable = [
        'title',
        'content',
        'phone',
        'email',
    ];
}
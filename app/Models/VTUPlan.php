<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VtuPlan extends Model
{
    use HasFactory;

    protected $fillable = ['provider_id', 'name', 'amount', 'validity', 'size', 'is_active'];

    public function provider()
    {
        return $this->belongsTo(VtuProvider::class, 'provider_id');
    }
}
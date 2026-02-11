<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VtuProvider extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'code', 'is_active', 'logo'];

    public function plans()
    {
        return $this->hasMany(VtuPlan::class, 'provider_id');
    }
}
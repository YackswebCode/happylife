<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'required_pv',
        'cash_reward',
        'other_reward',
        'description',
        'is_active'
    ];

    protected $casts = [
        'level' => 'integer',
        'required_pv' => 'integer',
        'cash_reward' => 'float',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function rankRewards()
    {
        return $this->hasMany(RankReward::class);
    }
}
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

    /**
     * Users who have achieved this rank.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Rank rewards associated with this rank.
     */
    public function rankRewards()
    {
        return $this->hasMany(RankReward::class);
    }

    /**
     * Get the next rank (higher level).
     */
    public function nextRank()
    {
        return self::where('level', $this->level + 1)
                    ->where('is_active', true)
                    ->first();
    }

    /**
     * Get the previous rank (lower level).
     */
    public function previousRank()
    {
        return self::where('level', $this->level - 1)
                    ->where('is_active', true)
                    ->first();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'code',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationships
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function pickupCenters()
    {
        return $this->hasMany(PickupCenter::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'state', 'name');
    }
}
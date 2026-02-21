<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name',
        'address',
        'contact_phone',
        'contact_person',
        'operating_hours',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationships
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // app/Models/PickupCenter.php

public function claims()
{
    return $this->hasMany(ProductClaim::class);
}


}
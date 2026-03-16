<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    protected $casts = [
        'type' => 'string',
    ];

    /**
     * Get the setting value, automatically decoding JSON if type is json.
     */
    public function getValueAttribute($value)
    {
        if ($this->type === 'json') {
            return json_decode($value, true) ?? [];
        }
        return $value;
    }

    /**
     * Set the value, automatically encoding if type is json.
     */
    public function setValueAttribute($value)
    {
        if ($this->type === 'json' && !is_string($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
}
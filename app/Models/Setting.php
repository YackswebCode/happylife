<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    public $timestamps = true;
    protected $fillable = ['key', 'value', 'type'];

    /**
     * Get a setting value by its key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        $value = $setting->value;
        $type = $setting->type;

        // Decode JSON if type is json
        if ($type === 'json') {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : $default;
        }

        // Remove surrounding double quotes from stored string (for text/image types)
        if (is_string($value) && preg_match('/^".*"$/', $value)) {
            $value = stripslashes(substr($value, 1, -1));
        }

        return $value ?? $default;
    }
}
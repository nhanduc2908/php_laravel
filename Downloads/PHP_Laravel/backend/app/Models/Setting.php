<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'system_settings';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'key', 'value', 'group', 'type', 'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean'
    ];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getGroup($group)
    {
        return static::where('group', $group)->pluck('value', 'key');
    }
}
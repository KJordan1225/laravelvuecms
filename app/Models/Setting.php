<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::query()->where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public static function setValue(
        string $key,
        mixed $value,
        string $group = 'general',
        string $type = 'text'
    ): self {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'type' => $type,
            ]
        );
    }
}

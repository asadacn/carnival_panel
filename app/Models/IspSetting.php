<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class IspSetting extends Model
{
    use HasFactory;

    protected $table = 'isp_settings';

    protected $fillable = [
        'key',
        'value'
    ];

    /**
     * Cache key for settings
     */
    const CACHE_KEY = 'app_isp_settings';

    /**
     * Get a setting by key with optional default fallback
     */
    public static function get($key, $default = null)
    {
        $settings = static::getAll();
        return $settings[$key] ?? $default;
    }

    /**
     * Set / Update a setting by key
     */
    public static function set($key, $value)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        static::clearCache();

        return $setting;
    }

    /**
     * Get all settings as key => value associative array with caching
     */
    public static function getAll()
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            try {
                return static::pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /**
     * Clear cached settings
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get ISP Logo URL with fallback to default logo
     */
    public static function getLogoUrl()
    {
        $logo = static::get('isp_logo');
        if ($logo && file_exists(public_path('uploads/settings/' . $logo))) {
            return asset('uploads/settings/' . $logo);
        }

        if (file_exists(public_path('img/logo.png'))) {
            return asset('img/logo.png');
        }

        return asset('img/logo.png');
    }

    /**
     * Get ISP Favicon URL with fallback
     */
    public static function getFaviconUrl()
    {
        $favicon = static::get('isp_favicon');
        if ($favicon && file_exists(public_path('uploads/settings/' . $favicon))) {
            return asset('uploads/settings/' . $favicon);
        }

        return static::getLogoUrl();
    }
}

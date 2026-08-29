<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Isp extends Model
{
    use HasFactory;

    protected $table = 'isps';

    protected $fillable = [
        'isp_code',
        'isp_name',
        'isp_tagline',
        'isp_logo',
        'isp_favicon',
        'phone',
        'billing_phone',
        'email',
        'website',
        'address',
        'currency_symbol',
        'invoice_title',
        'payment_instruction',
        'payment_methods',
        'payment_number',
        'invoice_footer',
        'signatory_title',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    const CACHE_KEY_ALL = 'all_isp_profiles';

    /**
     * Boot model events
     */
    protected static function booted()
    {
        static::saved(function () {
            static::clearCache();
        });

        static::deleted(function () {
            static::clearCache();
        });
    }

    /**
     * Clear ISP cache
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY_ALL);
    }

    /**
     * Get all ISP profiles cached
     */
    public static function getAllCached()
    {
        return Cache::remember(self::CACHE_KEY_ALL, 3600, function () {
            return static::orderBy('is_default', 'desc')->orderBy('isp_name', 'asc')->get();
        });
    }

    /**
     * Find ISP profile by isp_code with fallback to default ISP
     */
    public static function forCode($ispCode = null)
    {
        $all = static::getAllCached();

        if (!empty($ispCode)) {
            $normalized = strtolower(trim((string) $ispCode));
            $found = $all->firstWhere('isp_code', $normalized);
            if ($found) {
                return $found;
            }
        }

        // Fallback to default ISP or first available
        return $all->firstWhere('is_default', true) ?? $all->first();
    }

    /**
     * Get default ISP profile
     */
    public static function getDefault()
    {
        return static::forCode(null);
    }

    /**
     * Get Logo URL for this ISP with fallback to default img/logo.png
     */
    public function getLogoUrlAttribute()
    {
        if ($this->isp_logo && file_exists(public_path('uploads/settings/' . $this->isp_logo))) {
            return asset('uploads/settings/' . $this->isp_logo);
        }

        if (file_exists(public_path('img/logo.png'))) {
            return asset('img/logo.png');
        }

        return asset('img/logo.png');
    }

    /**
     * Get Favicon URL for this ISP
     */
    public function getFaviconUrlAttribute()
    {
        if ($this->isp_favicon && file_exists(public_path('uploads/settings/' . $this->isp_favicon))) {
            return asset('uploads/settings/' . $this->isp_favicon);
        }

        return $this->logo_url;
    }
}

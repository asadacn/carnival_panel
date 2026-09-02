<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Package
 * @package App\Models
 * @version November 21, 2021, 7:38 am UTC
 *
 * @property string $title
 * @property integer $price
 * @property string $descriptoin
 * @property string $isp_code
 */
class Package extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'packages';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'title',
        'price',
        'descriptoin',
        'isp_code'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'price' => 'integer',
        'descriptoin' => 'string',
        'isp_code' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|string|max:255',
        'price' => 'required|integer',
        'descriptoin' => 'nullable|string',
        'isp_code' => 'required|string|max:50'
    ];
    
    public function scopeIsp($query, string $isp_code)
    {
        return $query->where('isp_code', strtolower($isp_code));
    }

    /**
     * Packages belonging to a specific ISP, with fallback to default ISP
     */
    public static function forIsp(string $isp_code, ?bool $withFallback = true)
    {
        $isp_code = strtolower(trim($isp_code));
        $query = static::where('isp_code', $isp_code);

        if ($withFallback) {
            $defaultIsp = config('app.isp_code', 'carnival');
            if ($isp_code !== strtolower($defaultIsp)) {
                $query->orWhere('isp_code', strtolower($defaultIsp));
            }
        }

        return $query->get();
    }

    /**
     * Find a package by title for a specific ISP, with fallback to default ISP
     */
    public static function findByTitleForIsp(string $title, string $isp_code): ?self
    {
        $isp_code = strtolower(trim($isp_code));
        $pkg = static::where('title', $title)->where('isp_code', $isp_code)->first();

        if (!$pkg) {
            $defaultIsp = config('app.isp_code', 'carnival');
            if ($isp_code !== strtolower($defaultIsp)) {
                $pkg = static::where('title', $title)->where('isp_code', strtolower($defaultIsp))->first();
            }
        }

        return $pkg;
    }
}

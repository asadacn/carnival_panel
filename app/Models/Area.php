<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Area
 * @package App\Models
 * @version January 6, 2022, 4:14 pm UTC
 *
 * @property string $code
 * @property string $area
 * @property string $description
 */
class Area extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'areas';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'code',
        'area',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'code' => 'string',
        'area' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'code' => 'nullable',
        'area' => 'required',
        'description' => 'nullable'
    ];

    
}

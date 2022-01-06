<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Collector
 * @package App\Models
 * @version January 6, 2022, 4:35 pm UTC
 *
 * @property string $name
 * @property string $fathers_name
 * @property string $contact
 * @property string $address
 * @property string $nid
 */
class Collector extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'collectors';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'fathers_name',
        'contact',
        'address',
        'nid'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'fathers_name' => 'string',
        'contact' => 'string',
        'address' => 'string',
        'nid' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'contact' => 'max:12',
        'nid' => 'max:17'
    ];

    
}

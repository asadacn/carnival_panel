<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class HotspotClient
 * @package App\Models
 * @version September 17, 2023, 11:30 pm +06
 *
 * @property string $name
 * @property string $contact
 * @property string $cable
 * @property string $cable_owner
 * @property string $onu_mac
 * @property string $onu_owner
 * @property string $adrress
 */
class HotspotClient extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'hotspot_clients';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'contact',
        'cable',
        'cable_owner',
        'onu_mac',
        'onu_owner',
        'adrress'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'contact' => 'string',
        'cable' => 'string',
        'cable_owner' => 'string',
        'onu_mac' => 'string',
        'onu_owner' => 'string',
        'adrress' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'contact' => 'nullable',
        'cable' => 'nullable',
        'cable_owner' => 'required',
        'onu_mac' => 'nullable',
        'onu_owner' => 'nullable',
        'adrress' => 'nullable'
    ];


}

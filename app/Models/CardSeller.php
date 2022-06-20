<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CardSeller
 * @package App\Models
 * @version June 6, 2022, 7:21 pm UTC
 *
 * @property string $name
 * @property string $contact
 * @property string $shopName
 * @property string $village
 * @property string $union
 * @property string $address
 */
class CardSeller extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'card_sellers';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'contact',
        'shopName',
        'village',
        'union',
        'address'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'contact' => 'string',
        'shopName' => 'string',
        'village' => 'string',
        'union' => 'string',
        'address' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'contact' => 'nullable|numeric',
        'shopName' => 'nullable',
        'village' => 'nullable',
        'union' => 'nullable',
        'address' => 'nullable'
    ];


}

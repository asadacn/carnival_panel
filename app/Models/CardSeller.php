<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CardSeller
 * @package App\Models
 * @version September 27, 2022, 8:35 pm UTC
 *
 * @property string $seller
 * @property string $contact
 * @property string $store_title
 * @property string $address
 */
class CardSeller extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'card_sellers';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'seller',
        'contact',
        'store_title',
        'address'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'seller' => 'string',
        'contact' => 'string',
        'store_title' => 'string',
        'address' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'seller' => 'required',
        'contact' => 'required',
        'store_title' => 'nullable',
        'address' => 'nullable'
    ];

    
}

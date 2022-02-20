<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SMS_TEMPALTE
 * @package App\Models
 * @version February 20, 2022, 1:57 pm UTC
 *
 * @property string $title
 * @property string $sms_template
 */
class SMS_TEMPALTE extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 's_m_s__t_e_m_p_a_l_t_e_s';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'title',
        'sms_template'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'sms_template' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'sms_template' => 'required'
    ];

    
}

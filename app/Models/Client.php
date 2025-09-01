<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Client
 * @package App\Models
 *
 * @property string $name
 * @property string $contact
 * @property string $secondary_contact
 * @property string $email
 * @property string $address
 * @property string $package
 * @property string $username
 * @property string $password
 * @property string $onu_mac
 * @property string $onu_serial
 * @property string $onu_brand
 * @property boolean $onu_free
 * @property boolean $onu_returned
 * @property string $onu_owner
 * @property integer $cable
 * @property boolean $cable_returned
 * @property string $cable_owner
 * @property string $billing_type
 * @property string $gps_location
 * @property \Carbon\Carbon $expiration
 * @property string $status
 * @property string $comment
 */
class Client extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'clients';

    protected $dates = ['deleted_at', 'expiration'];

    protected $fillable = [
        'name',
        'contact',
        'secondary_contact',
        'email',
        'address',
        'package',
        'username',
        'password',
        'Onu_mac',
        'onu_serial',
        'onu_brand',
        'onu_free',
        'onu_returned',
        'onu_owner',
        'cable',
        'cable_returned',
        'cable_owner',
        'billing_type',
        'gps_location',
        'expiration',
        'status',
        'comment',
    ];

    protected $casts = [
        'name'             => 'string',
        'contact'          => 'string',
        'secondary_contact'=> 'string',
        'email'            => 'string',
        'address'          => 'string',
        'package'          => 'string',
        'username'         => 'string',
        'password'         => 'string',
        'Onu_mac'          => 'string',
        'onu_serial'       => 'string',
        'onu_brand'        => 'string',
        'onu_free'         => 'boolean',
        'onu_returned'     => 'boolean',
        'onu_owner'        => 'string',
        'cable'            => 'integer',
        'cable_returned'   => 'boolean',
        'cable_owner'      => 'string',
        'billing_type'     => 'string',
        'gps_location'     => 'string',
        'expiration'       => 'datetime',
        'status'           => 'string', // registered | expired
        'comment'          => 'string',
    ];

    public static $rules = [
        'name'             => 'required|string|max:255',
        'contact'          => 'nullable|string|max:255',
        'secondary_contact'=> 'nullable|string|max:255',
        'email'            => 'nullable|email|max:255',
        'address'          => 'nullable|string|max:255',
        'package'          => 'required|string|max:255',
        'username'         => 'nullable|string|max:255',
        'password'         => 'nullable|string|max:255',
        'Onu_mac'          => 'nullable|string|max:255',
        'onu_serial'       => 'nullable|string|max:255',
        'onu_brand'        => 'nullable|string|max:255',
        'onu_free'         => 'nullable|boolean',
        'onu_returned'     => 'nullable|boolean',
        'onu_owner'        => 'nullable|string|max:255',
        'cable'            => 'nullable|integer',
        'cable_returned'   => 'nullable|boolean',
        'cable_owner'      => 'nullable|string|max:255',
        'billing_type'     => 'nullable|string|max:255',
        'gps_location'     => 'nullable|string|max:255',
        'expiration'       => 'nullable|date',
        'status'           => 'required|in:registered,expired',
        'comment'          => 'nullable|string|max:500',
    ];

    public function getExpirationAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d-m-y') . ' * ' . Carbon::parse($value)->diffForHumans();
        }
        return null;
    }
}

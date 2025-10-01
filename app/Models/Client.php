<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'name'              => 'string',
        'contact'           => 'string',
        'secondary_contact' => 'string',
        'email'             => 'string',
        'address'           => 'string',
        'package'           => 'string',
        'username'          => 'string',
        'password'          => 'string',
        'Onu_mac'           => 'string',
        'onu_serial'        => 'string',
        'onu_brand'         => 'string',
        'onu_free'          => 'boolean',
        'onu_returned'      => 'boolean',
        'onu_owner'         => 'string',
        'cable'             => 'integer',
        'cable_returned'    => 'boolean',
        'cable_owner'       => 'string',
        'billing_type'      => 'string',
        'gps_location'      => 'string',
        'expiration'        => 'datetime',
        'status'            => 'string',
        'comment'           => 'string',
    ];

    public static $rules = [
        'name'              => 'required|string|max:255',
        'contact'           => 'nullable|string|max:255',
        'secondary_contact' => 'nullable|string|max:255',
        'email'             => 'nullable|email|max:255',
        'address'           => 'nullable|string|max:255',
        'package'           => 'required|string|max:255',
        'username'          => 'nullable|string|max:255',
        'password'          => 'nullable|string|max:255',
        'Onu_mac'           => 'nullable|string|max:255',
        'onu_serial'        => 'nullable|string|max:255',
        'onu_brand'         => 'nullable|string|max:255',
        'onu_free'          => 'nullable|boolean',
        'onu_returned'      => 'nullable|boolean',
        'onu_owner'         => 'nullable|string|max:255',
        'cable'             => 'nullable|integer',
        'cable_returned'    => 'nullable|boolean',
        'cable_owner'       => 'nullable|string|max:255',
        'billing_type'      => 'nullable|string|max:255',
        'gps_location'      => 'nullable|string|max:255',
        'expiration'        => 'nullable|date',
        'status'            => 'required|in:Registered,Expired',
        'comment'           => 'nullable|string|max:500',
    ];

    /**
     * Custom formatted expiration date for UI
     */
    public function getExpirationFormattedAttribute()
    {
        if (!empty($this->attributes['expiration'])) {
            $date = Carbon::parse($this->attributes['expiration']);
            return $date->format('d-m-Y') . ' [' . $date->diffForHumans() . ']';
        }
        return null;
    }
}

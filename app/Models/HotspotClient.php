<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

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
 * @property integer $package_days
 * @property string $status
 * @property \Carbon\Carbon $activated_at
 * @property \Carbon\Carbon $expires_at
 */
class HotspotClient extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'hotspot_clients';

    protected $dates = ['deleted_at', 'activated_at', 'expires_at'];

    public $fillable = [
        'name',
        'contact',
        'cable',
        'cable_owner',
        'onu_mac',
        'onu_owner',
        'adrress',
        'package_days',
        'status',
        'activated_at',
        'expires_at'
    ];

    protected $casts = [
        'name' => 'string',
        'contact' => 'string',
        'cable' => 'string',
        'cable_owner' => 'string',
        'onu_mac' => 'string',
        'onu_owner' => 'string',
        'adrress' => 'string',
        'package_days' => 'integer',
        'status' => 'string',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public static $rules = [
        'name' => 'required',
        'contact' => 'nullable',
        'cable' => 'nullable',
        'cable_owner' => 'required',
        'onu_mac' => 'nullable',
        'onu_owner' => 'nullable',
        'adrress' => 'nullable'
    ];

    /**
     * Check if package is expired
     *
     * @return bool
     */
    public function isExpired()
    {
        if (!$this->expires_at) {
            return false;
        }

        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * Activate or Renew Package
     *
     * @param int $days
     */
    public function activatePackage($days)
    {
        $this->package_days = $days;
        $this->activated_at = Carbon::now();
        $this->expires_at = Carbon::now()->addDays($days);
        $this->status = 'active';
        $this->save();
    }

    /**
     * Deactivate Package
     */
    public function deactivatePackage()
    {
        $this->status = 'inactive';
        $this->save();
    }
}

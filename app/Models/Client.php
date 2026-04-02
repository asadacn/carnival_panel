<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'clients';

    protected $dates = ['deleted_at', 'expiration'];

    protected $fillable = [
        'name',
        'contact',
        'isp_code',
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
        'isp_code'          => 'string',
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
        'isp_code'          => 'required|string|max:255',
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
        'status'            => 'required|in:Registered,Active,Expired',
        'comment'           => 'nullable|string|max:500',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────

    /** Filter by ISP: Client::isp('carnival')->get() */
    public function scopeIsp($query, string $isp_code)
    {
        return $query->where('isp_code', strtolower($isp_code));
    }

    /** Client::active()->get() */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /** Client::expired()->get() */
    public function scopeExpired($query)
    {
        return $query->where('status', 'Expired');
    }

    /** Client::registered()->get() */
    public function scopeRegistered($query)
    {
        return $query->where('status', 'Registered');
    }

    /** Clients expiring within N days: Client::expiringWithin(7)->get() */
    public function scopeExpiringWithin($query, int $days)
    {
        return $query->whereBetween('expiration', [now(), now()->addDays($days)]);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    /** e.g. "25-12-2025 [in 3 days]" */
    public function getExpirationFormattedAttribute(): ?string
    {
        if (!empty($this->attributes['expiration'])) {
            $date = Carbon::parse($this->attributes['expiration']);
            return $date->format('d-m-Y') . ' [' . $date->diffForHumans() . ']';
        }
        return null;
    }

    /** true if expiration is in the past */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiration && $this->expiration->isPast();
    }

    /** true if expiring within 7 days */
    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->expiration
            && !$this->expiration->isPast()
            && $this->expiration->diffInDays(now()) <= 7;
    }

    // ── Billing Relationships ─────────────────────────────────────────────────

    /**
     * Client has many DueBills
     */
    public function dueBills()
    {
        return $this->hasMany(DueBill::class);
    }

    /**
     * Client has many DueBillPayments
     */
    public function dueBillPayments()
    {
        return $this->hasMany(DueBillPayment::class);
    }

    /**
     * Get client's unpaid bills
     */
    public function unpaidDueBills()
    {
        return $this->hasMany(DueBill::class)->unpaid();
    }

    /**
     * Get client's overdue bills
     */
    public function overdueDueBills()
    {
        return $this->hasMany(DueBill::class)->overdue();
    }

    /**
     * Get total due amount
     */
    public function getTotalDueAttribute()
    {
        return $this->dueBills()
            ->unpaid()
            ->sum(\DB::raw('amount - paid_amount'));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DueBillPayment extends Model
{
    use HasFactory;

    protected $table = 'due_bill_payments';

    protected $fillable = [
        'due_bill_id',
        'client_id',
        'payment_date',
        'amount',
        'payment_method',
        'transaction_id',
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    /**
     * Payment belongs to DueBill
     */
    public function dueBill()
    {
        return $this->belongsTo(DueBill::class);
    }

    /**
     * Payment belongs to Client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Get payments for specific bill
     */
    public function scopeForBill($query, $billId)
    {
        return $query->where('due_bill_id', $billId);
    }

    /**
     * Get payments for specific client
     */
    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Get payments within date range
     */
    public function scopeInDateRange($query, $fromDate, $toDate)
    {
        return $query->whereBetween('payment_date', [$fromDate, $toDate]);
    }
}

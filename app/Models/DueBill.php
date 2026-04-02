<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DueBill extends Model
{
    use HasFactory;

    protected $table = 'due_bills';

    protected $fillable = [
        'client_id',
        'month',
        'year',
        'bill_date',
        'due_date',
        'amount',
        'paid_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'bill_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    /**
     * DueBill belongs to Client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * DueBill has many payments
     */
    public function payments()
    {
        return $this->hasMany(DueBillPayment::class);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    /**
     * Get remaining balance
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->amount - $this->paid_amount;
    }

    /**
     * Get payment percentage
     */
    public function getPaymentPercentageAttribute()
    {
        return $this->amount > 0 ? round(($this->paid_amount / $this->amount) * 100, 1) : 0;
    }

    /**
     * Check if bill is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->due_date < Carbon::now()->toDateString() && $this->status !== 'paid';
    }

    /**
     * Get formatted month-year
     */
    public function getMonthYearAttribute()
    {
        return Carbon::createFromDate($this->year, $this->month, 1)->format('F Y');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Get unpaid bills
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['unpaid', 'partially_paid', 'overdue']);
    }

    /**
     * Get bills for specific client
     */
    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Get bills for specific month
     */
    public function scopeForMonth($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    /**
     * Get overdue bills
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', Carbon::now()->toDateString())
                     ->whereIn('status', ['unpaid', 'partially_paid', 'overdue']);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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
        'notes',
        'reminder_sent_at'
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'bill_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'reminder_sent_at' => 'datetime',
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

    /**
     * Send one reminder per bill per calendar day.
     */
    public function sendReminder(): bool
    {
        if ($this->status === 'paid' || $this->remaining_balance <= 0 || !$this->client || !$this->client->contact) {
            return false;
        }

        if ($this->reminder_sent_at && $this->reminder_sent_at->isToday()) {
            return false;
        }

        $ispCode = $this->client->isp_code ?? null;
        $currency = isp_setting('currency_symbol', '৳', $ispCode);
        $instruction = isp_setting('payment_instruction', config('sms.payment_instruction', 'Please pay through bKash/Nagad.'), $ispCode);
        $methods = isp_setting('payment_methods', config('sms.payment_methods', 'bKash/Nagad'), $ispCode);
        $paymentNumber = isp_setting('payment_number', config('sms.payment_number', ''), $ispCode);
        $ispName = isp_name($ispCode, ucfirst($this->client->isp_code ?? 'Carnival'));

        $message = "প্রিয় {$this->client->name},\n"
            . "গ্রাহক আইডি: {$this->client->username}\n"
            . $this->month_year . " মাসের বকেয়া: {$currency}"
            . number_format($this->remaining_balance, 2)
            . "\n" . $instruction . "\n"
            . $methods . ': ' . $paymentNumber
            . "\n- " . $ispName;

        if (!sms($this->client->contact, $message)) {
            Log::warning('Due bill reminder SMS failed', ['due_bill_id' => $this->id]);
            return false;
        }

        $this->forceFill(['reminder_sent_at' => now()])->save();
        return true;
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

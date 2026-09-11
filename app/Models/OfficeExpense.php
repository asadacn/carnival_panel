<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficeExpense extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'office_expenses';

    protected $dates = ['expense_date', 'deleted_at'];

    protected $fillable = [
        'expense_date',
        'category',
        'title',
        'description',
        'amount',
        'payment_method',
        'reference_number',
        'notes',
        'receipt_path',
        'created_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'float',
        'created_by' => 'integer',
    ];

    public const CATEGORIES = [
        'office_rent' => 'Office Rent',
        'electricity' => 'Electricity',
        'internet' => 'Internet & Bandwidth',
        'salary' => 'Salary & Wages',
        'equipment' => 'Equipment & Devices',
        'maintenance' => 'Maintenance & Repair',
        'transport' => 'Transport',
        'food' => 'Food & Refreshments',
        'marketing' => 'Marketing',
        'software' => 'Software & Subscriptions',
        'bank_charge' => 'Bank Charge',
        'other' => 'Other',
    ];

    public const PAYMENT_METHODS = [
        'cash' => 'Cash',
        'bkash' => 'bKash',
        'nagad' => 'Nagad',
        'bank' => 'Bank Transfer',
        'card' => 'Card',
        'other' => 'Other',
    ];

    public static function categories()
    {
        return self::CATEGORIES;
    }

    public static function paymentMethods()
    {
        return self::PAYMENT_METHODS;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCategoryLabelAttribute()
    {
        return self::CATEGORIES[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }

    public function getPaymentMethodLabelAttribute()
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    public function getReceiptUrlAttribute()
    {
        return $this->receipt_path ? asset('storage/' . $this->receipt_path) : null;
    }
}

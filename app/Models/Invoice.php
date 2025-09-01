<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'invoice_number',
        'amount',
        'discount',
        'total_payable',
        'paid',
        'due',
        'billing_date',
        'due_date',
        'status',
        'note'
    ];

    // Relation: Invoice belongs to Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relation: Invoice has many Payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

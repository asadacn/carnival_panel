<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'invoice_id',
        'amount',
        'due',
        'payment_method',
        'paid_date',
        'transaction_id',
        'note'
    ];

    // Relation: Payment belongs to Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relation: Payment belongs to Invoice (optional)
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}

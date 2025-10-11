<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'telegram_id', 'whatsapp_number', 'status'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}

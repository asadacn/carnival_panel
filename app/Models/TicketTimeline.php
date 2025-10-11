<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketTimeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'action',
        'performed_by',
        'note',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

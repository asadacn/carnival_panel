<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'technician_id', 'complain_type_id',
        'description', 'status', 'priority', 'opened_at', 'closed_at'
    ];

    protected $dates = ['opened_at','closed_at'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function complainType()
    {
        return $this->belongsTo(ComplainType::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SMSLOG extends Model
{
    use HasFactory;

    protected $table = 'sms_logs';

    protected $fillable = [
        'client_id',
        'client_identifier',
        'campaign_id',
        'user_id',
        'contact',
        'sms',
        'character_count',
        'sms_count',
        'message_type',
        'encoding',
        'gateway',
        'gateway_message_id',
        'status',
        'error_message',
        'gateway_response',
        'sent_at',
    ];

    protected $casts = [
        'sent_at'          => 'datetime',
        'character_count'  => 'integer',
        'sms_count'        => 'integer',
        'gateway_response' => 'array',
    ];

    /**
     * Relationship with Client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Relationship with SmsCampaign (if sent as part of a bulk campaign)
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(SmsCampaign::class, 'campaign_id');
    }

    /**
     * Relationship with User (operator who initiated the send)
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Convenient alias getter for message content
     */
    public function getMessageAttribute()
    {
        return $this->sms;
    }

    /**
     * Convenient alias setter for message content
     */
    public function setMessageAttribute($value)
    {
        $this->attributes['sms'] = $value;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmsCampaign extends Model
{
    use HasFactory;

    protected $table = 'sms_campaigns';

    protected $fillable = [
        'user_id',
        'title',
        'target_group',
        'isp_code',
        'message_template',
        'total_recipients',
        'successful_count',
        'failed_count',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at'          => 'datetime',
        'total_recipients' => 'integer',
        'successful_count' => 'integer',
        'failed_count'     => 'integer',
    ];

    /**
     * The admin user who initiated this campaign.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * All individual SMS logs sent as part of this campaign.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(SMSLOG::class, 'campaign_id');
    }
}

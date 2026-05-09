<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientComment extends Model
{
    protected $table = 'client_comments';

    protected $fillable = [
        'client_id',
        'user_id',
        'body',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The client this comment belongs to.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * The user who wrote the comment.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Convenience: formatted time-ago string.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at?->diffForHumans() ?? '';
    }

    /**
     * Short name of the author.
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->author?->name ?? 'System';
    }

    /**
     * Author initials for avatar fallback.
     */
    public function getAuthorInitialsAttribute(): string
    {
        $name = $this->author_name;
        $words = explode(' ', trim($name));
        $initials = strtoupper(substr($words[0], 0, 1));
        if (count($words) > 1) {
            $initials .= strtoupper(substr(end($words), 0, 1));
        }
        return $initials;
    }
}

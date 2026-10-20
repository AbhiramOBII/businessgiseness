<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityRegistration extends Model
{
    protected $fillable = [
        'community_event_id',
        'name',
        'email',
        'company',
        'phone',
        'ip_address',
        'user_agent',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(CommunityEvent::class, 'community_event_id');
    }
}

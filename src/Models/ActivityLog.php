<?php

namespace Sagor\ActivityLog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $table = 'sagor_activity_log';

    protected $fillable = [
        'causer_id',
        'causer_type',
        'activity_type',
        'description',
        'subject_id',
        'subject_type',
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}

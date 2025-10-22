<?php

namespace Sagor\ActivityLog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $table = 'automatic_activity_log';

    protected $fillable = [
        'causer_id',
        'causer_type',
        'action_type',
        'description',
        'affected_model_id',
        'affected_model_type',
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public function affectedModel(): MorphTo
    {
        return $this->morphTo();
    }
}

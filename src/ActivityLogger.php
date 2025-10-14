<?php

namespace Sagor\ActivityLog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Sagor\ActivityLog\Models\ActivityLog;

class ActivityLogger
{
    protected ?Model $causer = null;
    protected ?Model $affectedModel = null;
    protected ?string $actionType = null;
    protected array $properties = [];

    public function causedBy(?Model $causer): self
    {
        $this->causer = $causer;
        return $this;
    }

    public function on(?Model $affectedModel): self
    {
        $this->affectedModel = $affectedModel;
        return $this;
    }

    public function withActionType(string $actionType): self
    {
        $this->actionType = $actionType;
        return $this;
    }

    public function withProperties(array $properties): self
    {
        $this->properties = $properties;
        return $this;
    }

    public function log(string $description): ?Model
    {
        $causer = $this->causer ?? Auth::user();

        $log = new ActivityLog();
        $log->description = $description;
        $log->action_type = $this->actionType;

        if ($causer) {
            $log->causer()->associate($causer);
        }

        if ($this->affectedModel) {
            $log->affectedModel()->associate($this->affectedModel);
        }

        $log->ip_address = $this->properties['ip_address'] ?? Request::ip();
        $log->user_agent = $this->properties['user_agent'] ?? Request::userAgent();
        $log->url = $this->properties['url'] ?? Request::fullUrl();
        $log->method = $this->properties['method'] ?? Request::method();

        $log->save();

        return $log;
    }
}

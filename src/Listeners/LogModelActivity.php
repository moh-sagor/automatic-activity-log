<?php

namespace Sagor\ActivityLog\Listeners;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogModelActivity
{
    public function handle(string $eventName, array $data): void
    {
        [$action, $model] = $this->parseEvent($eventName, $data);

        if (!$model instanceof Model) {
            return;
        }

        $modelName = class_basename($model);

        activity()
            ->causedBy(Auth::user())
            ->on($model)
            ->withActionType($action)
            ->log("The {$modelName} model was {$action}");
    }

    protected function parseEvent(string $eventName, array $data): array
    {
        // eventName is like 'eloquent.created: App\Models\User'
        $action = explode('.', $eventName)[1]; // created, updated, deleted
        $action = explode(':', $action)[0];

        $modelInstance = $data[0] ?? null;

        return [$action, $modelInstance];
    }
}


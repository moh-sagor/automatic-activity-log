<?php

namespace Sagor\ActivityLog\Listeners;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogModelActivity
{
    public function handle(string $eventName, array $data): void
    {
        [$event, $model] = $this->parseEvent($eventName);

        if (!$model instanceof Model) {
            return;
        }

        $modelName = class_basename($model);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($model)
            ->withType('model')
            ->log("Model {$modelName} was {$event}");
    }

    protected function parseEvent(string $eventName): array
    {
        $parts = explode(': ', $eventName);
        $event = explode('.', $parts[0])[1]; // eloquent.created -> created
        $model = $parts[1] ?? null;

        // The event payload for `eloquent.*` contains the model instance
        // but we receive it as an argument in the handle method from the service provider.
        // This logic assumes the service provider correctly passes the model.
        // We will get the model instance from the data array.
        $modelInstance = $data[0] ?? null;

        return [$event, $modelInstance];
    }
}

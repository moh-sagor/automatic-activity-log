<?php

namespace Sagor\ActivityLog\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Sagor\ActivityLog\Models\ActivityLog;

class LogAuthActivity
{
    public function handle($event): void
    {
        $eventName = class_basename($event);

        switch ($eventName) {
            case 'Login':
                $this->logLogin($event);
                break;
            case 'Logout':
                $this->logLogout($event);
                break;
        }
    }

    protected function logLogin(Login $event): void
    {
        activity()
            ->causedBy($event->user)
            ->withType('auth')
            ->log('User logged in');
    }

    protected function logLogout(Logout $event): void
    {
        if (!$event->user) {
            return;
        }

        activity()
            ->causedBy($event->user)
            ->withType('auth')
            ->log('User logged out');
    }
}

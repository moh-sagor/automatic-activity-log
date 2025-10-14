<?php

namespace Sagor\ActivityLog;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Sagor\ActivityLog\Listeners\LogAuthActivity;
use Sagor\ActivityLog\Listeners\LogHttpActivity;
use Sagor\ActivityLog\Listeners\LogModelActivity;

class ActivityLogServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register migrations
        if (method_exists($this, 'loadMigrationsFrom')) {
            // For modern Laravel versions
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        } else {
            // For older Laravel versions (pre-5.3), migrations must be published
            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations')
            ], 'migrations');
        }

        $this->registerEventListeners();
    }

    public function register(): void
    {
        // No specific container bindings are needed.
    }

    protected function registerEventListeners(): void
    {
        // Authentication Events
        Event::listen(Login::class, [LogAuthActivity::class, 'handle']);
        Event::listen(Logout::class, [LogAuthActivity::class, 'handle']);

        // Model Events
        Event::listen('eloquent.created: *', [LogModelActivity::class, 'handle']);
        Event::listen('eloquent.updated: *', [LogModelActivity::class, 'handle']);
        Event::listen('eloquent.deleted: *', [LogModelActivity::class, 'handle']);
        Event::listen('eloquent.restored: *', [LogModelActivity::class, 'handle']);

        // HTTP Request Event (Laravel 5.4+)
        if (class_exists(RequestHandled::class)) {
            Event::listen(RequestHandled::class, [LogHttpActivity::class, 'handle']);
        }
    }
}

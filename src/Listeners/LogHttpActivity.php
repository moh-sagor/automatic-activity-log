<?php

namespace Sagor\ActivityLog\Listeners;

use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Auth;

class LogHttpActivity
{
    public function handle(RequestHandled $event): void
    {
        $request = $event->request;
        $method = strtoupper($request->getMethod());

        // We only log modifying requests, not GET or HEAD
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'])) {
            return;
        }

        activity()
            ->causedBy(Auth::user())
            ->withType('http')
            ->withProperties([
                'url' => $request->fullUrl(),
                'method' => $method,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log("A {$method} request was made to {$request->path()}");
    }
}

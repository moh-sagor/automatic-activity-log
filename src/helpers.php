<?php

use Sagor\ActivityLog\ActivityLogger;

if (!function_exists('activity')) {
    function activity(): ActivityLogger
    {
        return new ActivityLogger();
    }
}

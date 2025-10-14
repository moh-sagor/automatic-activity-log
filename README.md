# Sagor Activity Log

A simple, zero-configuration package to automatically log user activities in a Laravel application.

## Compatibility

This package is designed to be compatible with a wide range of Laravel and PHP versions.

- **Laravel**: `5.0` - `12.x`
- **PHP**: `7.2` - `8.4`

## Features

- **Automatic Logging**: No need to manually dispatch events or log activities. The package handles it for you.
- **Model Activity**: Logs created, updated, deleted, and restored events for all Eloquent models.
- **HTTP Requests**: Logs all `POST`, `PUT`, `PATCH`, and `DELETE` requests.
- **Authentication**: Logs user login and logout events.
- **Flexible**: Provides a helper function `activity()` for manual logging if needed.

## Installation

1.  **Add Repository to `composer.json`**

    Since this is a local package, you need to tell your main Laravel application's `composer.json` where to find it. Add the following `repositories` block to your `composer.json` file:

    ```json
    "repositories": [
        {
            "type": "path",
            "url": "packages/sagor/activity-log"
        }
    ]
    ```

2.  **Require the Package**

    Now, you can require the package using Composer:

    ```bash
    composer require sagor/activity-log
    ```

3.  **Run the Migration**

    The service provider automatically registers the database migration. Run the `migrate` command to create the `sagor_activity_log` table:

    ```bash
    php artisan migrate
    ```

## How It Works

This package listens for several built-in Laravel events to log activity automatically:

- `eloquent.*`: Captures all model changes.
- `Illuminate\Auth\Events\Login` & `Logout`: Captures authentication state changes.
- `Illuminate\Foundation\Http\Events\RequestHandled`: Captures incoming HTTP requests that modify data.

All data is stored in the `sagor_activity_log` table.

## Manual Logging

Although the package is designed to be automatic, you can manually log activities using the `activity()` helper function.

```php
activity()
    ->causedBy(auth()->user()) // The user who performed the action
    ->performedOn($someModel) // The model that was affected
    ->withType('custom') // A custom activity type
    ->log('A custom action was performed.'); // The description of the activity
```

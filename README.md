# 🧾 Sagor Activity Log

[![Packagist Version](https://img.shields.io/packagist/v/sagor/activity-log.svg)](https://packagist.org/packages/sagor/activity-log)
[![Laravel Version](https://img.shields.io/badge/Laravel-5.x--12.x-orange)]()
[![PHP Version](https://img.shields.io/badge/PHP-7.2--8.4-blue)]()
[![License](https://img.shields.io/badge/License-MIT-green)]()

A **zero-configuration Laravel package** to automatically log user activities, model changes, HTTP requests, and authentication events. Everything works **out-of-the-box**, no manual setup required.

---

## 📌 Compatibility

* **Laravel:** 5.x - 12.x
* **PHP:** 7.2 - 8.4

---

## ⚡ Features

* Automatic logging of all Eloquent model events (`created`, `updated`, `deleted`, `restored`)
* Logs user login and logout events
* Logs HTTP requests that modify data (`POST`, `PUT`, `PATCH`, `DELETE`)
* Manual logging with the `activity()` helper
* No configuration required

---

## 🛠 Installation and Setup (Full Process)

1. **Install via Composer**

```bash
composer require sagor/activity-log
```

2. **Run Migration**

The package automatically provides a migration. Run:

```bash
php artisan migrate
```

This will create a table called `sagor_activity_log` with the following structure:

| Column              | Type      | Description                                                 |
| ------------------- | --------- | ----------------------------------------------------------- |
| id                  | bigint    | Primary key                                                 |
| causer_type         | string    | Type of the user or system causing the action (polymorphic) |
| causer_id           | bigint    | ID of the user or system causing the action (polymorphic)   |
| action_type         | string    | Type of activity (e.g., created, updated, deleted)          |
| description         | longText  | Description of the activity                                 |
| affected_model_type | string    | Type of the affected model (polymorphic)                    |
| affected_model_id   | bigint    | ID of the affected model (polymorphic)                      |
| ip_address          | string    | IP address of the user performing the action                |
| user_agent          | text      | User agent of the requester                                 |
| url                 | string    | URL where the action occurred                               |
| method              | string    | HTTP method used                                            |
| created_at          | timestamp | Log creation time                                           |
| updated_at          | timestamp | Log update time                                             |

3. **How It Works**

The package automatically listens to Laravel events:

* `eloquent.*` → logs all model events (`created`, `updated`, `deleted`, `restored`)
* `Illuminate\Auth\Events\Login` & `Logout` → logs authentication events
* `Illuminate\Foundation\Http\Events\RequestHandled` → logs `POST`, `PUT`, `PATCH`, `DELETE` requests

All activities are stored automatically in the `sagor_activity_log` table. **No manual calls needed.**

---

## ✍️ Manual Logging Example

If you want to log custom actions, you can use the `activity()` helper:

```php
activity()
    ->causedBy(auth()->user())  // The user performing the action
    ->performedOn($someModel)   // The affected model
    ->withType('custom')        // Custom type (optional)
    ->log('A custom action was performed.'); // Description
```

Example:

```php
// Log a manual update for a post
activity()
    ->causedBy(auth()->user())
    ->performedOn($post)
    ->withType('custom')
    ->log('Updated a post manually.');
```

---

## 📚 Full Usage Flow

1. Install the package with Composer
2. Run `php artisan migrate` to create the log table
3. Automatically track:

* All model changes
* User login/logout events
* HTTP requests that modify data

4. Optionally, log custom actions using `activity()` helper
5. Check the `sagor_activity_log` table for logs

**No additional configuration is needed.** Just install, migrate, and it works.

---


## Route with controller
```
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('/activity-log', function (Request $request) {
    $query = DB::table('sagor_activity_log')->orderBy('created_at', 'desc');

    // Apply search filter if provided
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where('description', 'like', "%{$search}%")
              ->orWhere('action_type', 'like', "%{$search}%")
              ->orWhere('causer_type', 'like', "%{$search}%")
              ->orWhere('affected_model_type', 'like', "%{$search}%");
    }

    // Paginate results
    $logs = $query->paginate(10)->withQueryString();

    return view('activity-log', compact('logs', 'request'));
});
```
## Blade with Filter and pagination
```
@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="container">
    <h1 class="mb-4">Activity Logs</h1>

    <!-- Search Form -->
    <form method="GET" action="{{ url('/activity-log') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ $request->search }}">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Causer</th>
                <th>Action Type</th>
                <th>Description</th>
                <th>Affected Model</th>
                <th>IP Address</th>
                <th>User Agent</th>
                <th>URL</th>
                <th>Method</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->causer_type }} #{{ $log->causer_id }}</td>
                <td>{{ $log->action_type }}</td>
                <td>{{ $log->description }}</td>
                <td>{{ $log->affected_model_type }} #{{ $log->affected_model_id }}</td>
                <td>{{ $log->ip_address }}</td>
                <td>{{ $log->user_agent }}</td>
                <td>{{ $log->url }}</td>
                <td>{{ $log->method }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">No logs found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $logs->links() }}
    </div>
</div>
@endsection
```

## 🛡 License

MIT License. See the LICENSE file for details.

---

## 👤 Author

**Md Sagor Hossain**
Email: [sagorhassain4@gmail.com](mailto:sagorhassain4@gmail.com)
GitHub: [https://github.com/moh-sagor/automatic-activity-log](https://github.com/moh-sagor/automatic-activity-log)

---

## 🚀 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/AmazingFeature`
3. Commit your changes: `git commit -m 'Add some AmazingFeature'`
4. Push to the branch: `git push origin feature/AmazingFeature`
5. Open a Pull Request

---

## 🎯 Support

If you like this package, give it a ⭐ on GitHub!

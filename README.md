## Introduction

A Real time Chat application using Laravel 8.0 with jetstream & Inertia stack.
You can configure the server port in the socketio-chat-inertia\resources\views\app.blade.php file at:

```Javascript
 const socket = io("http://localhost:YOUR_PORT")
```

## Security

This minimal chat application is provided with all Laravel web security features (CSRF, SQL injection, XSS, ...) but it's a DEMO project so it may have out-of-date features that should not be exposed in production.

There's no end-to-end encryption between users conversations.

## Installation

```bash
git clone https://github.com/SimoneC03/socketio-chat-inertia
cd socketio-chat-inertia
composer install
npm install
npm run dev
# or: npm run watch

```

### Configure MySql Database

Copy the .env.example file, rename to .env and insert your db details

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=socketio_chat_inertia
DB_USERNAME=root
DB_PASSWORD=
```

### Create DB

Create a new database called <code>socketio_chat_inertia</code> or anything you've put in the DB_DATABASE key

### Migrate tables

```bash
php artisan migrate
```

### Serving on 8000 local port (127.0.0.1:8000)

```bash
php artisan serve
```

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

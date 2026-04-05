# xlock/laravel

Laravel middleware for [x-lock](https://x-lock.cloud) bot protection.

## Requirements

- PHP >= 8.1
- Laravel 10, 11, or 12

## Installation

```bash
composer require xlock/laravel
```

The service provider is auto-discovered. To publish the config file:

```bash
php artisan vendor:publish --tag=xlock-config
```

Add your site key to `.env`:

```
XLOCK_SITE_KEY=sk_your_site_key
```

## Usage

Apply the `xlock` middleware to any routes you want to protect:

```php
Route::middleware('xlock')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});
```

Or apply it to a single route:

```php
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('xlock');
```

## Configuration

| Key | Env Variable | Default | Description |
|-----|-------------|---------|-------------|
| `site_key` | `XLOCK_SITE_KEY` | `null` | Your x-lock site key |
| `api_url` | `XLOCK_API_URL` | `https://api.x-lock.cloud` | Enforcement API endpoint |
| `fail_open` | `XLOCK_FAIL_OPEN` | `true` | Allow requests when API is unreachable |

## How it works

The middleware intercepts POST requests and checks for an `x-lock` header token. It sends the token to the x-lock enforcement API for verification. If the token is missing or rejected, the request is blocked with a 403 response.

When `fail_open` is `true` (the default), requests are allowed through if the x-lock API is unreachable or returns an unexpected error.

## License

MIT

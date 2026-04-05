<?php

namespace XLock\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class XLockServiceProvider extends ServiceProvider
{
    public function boot(Router $router): void
    {
        $this->publishes([
            __DIR__ . '/../config/xlock.php' => config_path('xlock.php'),
        ], 'xlock-config');

        $router->aliasMiddleware('xlock', XLockMiddleware::class);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/xlock.php',
            'xlock'
        );
    }
}

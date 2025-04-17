<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\AdminMiddleware::class,
            'isManager' => \App\Http\Middleware\ManagerMiddleware::class,
            'isCashier' => \App\Http\Middleware\CashierMiddleware::class,
            'isCommon' => \App\Http\Middleware\CommonMiddleware::class,
            'isOnline' => \App\Http\Middleware\OnlineUserMiddleware::class,
            'checkRole' => \App\Http\Middleware\CheckRoleMiddleware::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

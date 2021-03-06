<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\Cors::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'          => \App\Http\Middleware\Authenticate::class,
        'auth.basic'    => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'         => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'auth.user'     => \App\Http\Middleware\UserAuthMiddleware::class,
        'csrf'          => \App\Http\Middleware\VerifyCsrfToken::class,
        'role'          => \App\Http\Middleware\EntrustRoleMiddleware::class,
        'permission'    => \App\Http\Middleware\EntrustPermissionMiddleware::class,
        'ability'       => \App\Http\Middleware\EntrustAbilityMiddleware::class,

    ];
}

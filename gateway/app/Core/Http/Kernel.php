<?php

namespace App\Core\Http;

//use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Gecche\Multidomain\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Core\Http\Middleware\TrustHosts::class,
        \App\Core\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Core\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Core\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Core\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Core\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle',
            'bindings',
            'cors',
        ],
    ];

    /**
     * The application's route middleware1.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Core\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Core\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        //'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'throttle' => \App\Core\Http\Middleware\NoThrottle::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'auth.company' => \App\Core\Http\Middleware\CheckAuthCompany::class,
        'auth.socket' => \App\Core\Http\Middleware\CheckAuthSocket::class,
        'auth.admin' => \App\Core\Http\Middleware\CheckAuthAdmin::class,
        'cors' =>  \Fruitcake\Cors\HandleCors::class,
    ];
}

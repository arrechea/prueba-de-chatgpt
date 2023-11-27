<?php

namespace App\Http;

use App\Http\Middleware\ApiMiddleware\ApiBrandMiddleware;
use App\Http\Middleware\ApiMiddleware\ApiLocationMiddleware;
use App\Http\Middleware\AuthCompany;
use App\Http\Middleware\AuthWidget;
use App\Http\Middleware\Gympass\WebhooksMiddleware;
use App\Http\Middleware\RejectInactiveAdmins;
use App\Http\Middleware\RejectInactiveUsers;
use App\Http\Middleware\RejectLoginUserInactives;
use App\Http\Middleware\System\BrandMiddleware;
use App\Http\Middleware\System\CompanyMiddleware;
use App\Http\Middleware\System\CreateLogSystem;
use App\Http\Middleware\System\GafaFitMiddleware;
use App\Http\Middleware\System\LocationMiddleware;
use Barryvdh\Cors\HandleCors;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

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
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        HandleCors::class,
        CreateLogSystem::class,
        // debugger::class
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web'   => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\LanguageMiddleware::class,
        ],
        'admin' => [
            RejectInactiveAdmins::class,
        ],

        'api'     => [
//            'throttle:60000,1',
            'bindings',
        ],
        'gympass_webhooks' => [
            WebhooksMiddleware::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin'        => \App\Http\Middleware\RedirectIfNotAdmin::class,
        'admin.guest'  => \App\Http\Middleware\RedirectIfAdmin::class,
        'auth'         => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic'   => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'     => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can'          => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'        => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'     => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'gafafit'      => GafaFitMiddleware::class,
        'company'      => CompanyMiddleware::class,
        'brand'        => BrandMiddleware::class,
        'location'     => LocationMiddleware::class,
        'apiUser'      => RejectInactiveUsers::class,
        'apiLoginUser' => RejectLoginUserInactives::class,
        'authCompany'  => AuthCompany::class,
        'api.brand'    => ApiBrandMiddleware::class,
        'api.location' => ApiLocationMiddleware::class,
        'scopes'       => \Laravel\Passport\Http\Middleware\CheckScopes::class,
        'scope'        => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
        'api.admin'    => \App\Http\Middleware\ApiAdmin::class,
        'widget'       => AuthWidget::class,
    ];
}

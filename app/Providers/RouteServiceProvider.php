<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();

        $this->mapOauthRoutes();

        $this->mapGympassRoutes();
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::group([
            'middleware' => [
                'web',
            ],
            'prefix'     => 'admin',
            'as'         => 'admin.',
            'namespace'  => "{$this->namespace}\Admin",
        ], function ($router) {
            require base_path('routes/admin.php');
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => [
                'api',
            ],
            'prefix'     => 'api',
            'as'         => 'api.',
            'namespace'  => "{$this->namespace}\Api",
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }

    protected function mapOauthRoutes()
    {
        Route::group([
            'middleware' => [
                'apiLoginUser',
            ],
            'prefix'     => 'oauth',
            'as'         => 'oauth.',
            'namespace'  => $this->namespace,
        ], function ($router) {
            require base_path('routes/oauth.php');
        });
    }

    protected function mapGympassRoutes()
    {
        Route::group([
//            'middleware' => [
//                'gympass',
//            ],
            'prefix'     => 'gympass',
            'as'         => 'gympass.',
            'namespace'  => "{$this->namespace}\Gympass",
        ], function ($router) {
            require base_path('routes/gympass.php');
        });
    }
}

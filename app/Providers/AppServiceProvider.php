<?php

namespace App\Providers;

use App\User;
use App\Observers\UserObserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Laravel\Passport\Http\Controllers\AccessTokenController as PassportAccessTokenController;
use Laravel\Passport\Http\Controllers\AccessTokenController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        User::observe(UserObserver::class);
        $appUrl = Config::get('app.url');
        if (strpos($appUrl, 'https', 0) === 0) {
            URL::forceScheme('https');
        }
        URL::forceRootUrl($appUrl);


//        Para ponerlo en ngrok si el sistio está en local
//        if (!empty( env('NGROK_URL') ) && $request->server->has('HTTP_X_ORIGINAL_HOST')) {
//            $this->app['url']->forceRootUrl(env('NGROK_URL'));
//        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
        $this->app->bind('\\', AccessTokenController::class, \App\Http\Controllers\AccessTokenController::class);
    }
}

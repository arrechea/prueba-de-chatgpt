<?php

namespace App\Providers;

use Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //Sistema de logueos usuarios
        Auth::provider('userProvider', function ($app, array $config) {
            return new UserServiceProvider($app['hash'], $config['model']);
        });

        Passport::routes(null, [
            'middleware' => [
                'apiLoginUser',
            ],
        ]);

        Passport::tokensCan([
            'admin'    => 'Admin scope',
            'client' => 'Final client scope',
        ]);
    }
}

<?php

namespace App\Providers;
use App;
use Illuminate\Support\ServiceProvider;

class WebhookServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // ---* GAFAPAY Service
        App::bind('gafapay',function() {
            return new \App\Librerias\Webhooks\GafaPay;
          });

        // ---* CLIENTSITES Service
        App::bind('clientsites',function() {
            return new \App\Librerias\Webhooks\ClientSites;
          });
    }
}

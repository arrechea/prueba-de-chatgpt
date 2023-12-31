<?php

namespace App\Providers;

use App\Channels\SendGridMailChannel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;

class SendGridChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Notification::extend('sendgrid', function ($app) {
            return new SendGridMailChannel(
                new \SendGrid(
                    $this->app['config']['services.sendgrid.api_key']
                )
            );
        });
    }
}

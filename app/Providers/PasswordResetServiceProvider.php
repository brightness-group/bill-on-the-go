<?php


namespace App\Providers;

use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Support\ServiceProvider;


class PasswordResetServiceProvider extends \Illuminate\Auth\Passwords\PasswordResetServiceProvider
{
    protected function registerPasswordBroker()
    {
        $this->app->singleton('auth.password', function ($app) {
            return new  \App\Actions\Fortify\PasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {
            return $app->make('auth.password')->broker();
        });
    }
}

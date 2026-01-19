<?php

namespace App\Providers;

use App\Repositories\CustomTokenRepository;
use Laravel\Passport\PassportServiceProvider;

class CustomPassportServiceProvider extends PassportServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\Laravel\Passport\TokenRepository::class, function ($app) {
            return new CustomTokenRepository();
        });

        parent::register();
    }
}
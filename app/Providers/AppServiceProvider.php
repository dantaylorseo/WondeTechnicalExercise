<?php

namespace App\Providers;

use Wonde\Client as WondeClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WondeClient::class, function () {
            $client = new WondeClient(config('wonde.api_token'));
            return $client;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

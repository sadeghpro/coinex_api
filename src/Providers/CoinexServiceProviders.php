<?php

namespace Coinex\Providers;

use Illuminate\Support\ServiceProvider;

class CoinexServiceProviders extends ServiceProvider
{

    public function boot()
    {

    }

    public function register()
    {
        $this->app->bind('coinex',function() {
            return new Coinex/CoinexApi;
        });
    }
}

<?php

namespace Coinex\Providers;

use Illuminate\Support\ServiceProvider;
use \Coinex\CoinexApi;

class CoinexServiceProviders extends ServiceProvider
{

    public function boot()
    {

    }

    public function register()
    {
        $this->app->bind('coinex',function() {
            return new CoinexApi;
        });
    }
}

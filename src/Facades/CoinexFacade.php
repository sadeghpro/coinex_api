<?php

namespace Coinex\Facades;

use Illuminate\Support\Facades\Facade;

class CoinexFacade extends Facade
{
    protected static function getFacadeAccessor() { return 'coinex'; }
}

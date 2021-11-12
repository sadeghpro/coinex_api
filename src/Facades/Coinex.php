<?php

namespace Coinex\Facades;

use Illuminate\Support\Facades\Facade;

class Coinex extends Facade
{
    protected static function getFacadeAccessor() { return 'coinex_api'; }
}

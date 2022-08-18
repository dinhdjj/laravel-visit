<?php

namespace Dinhdjj\Visit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dinhdjj\Visit\Visit
 */
class Visit extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'visit';
    }
}

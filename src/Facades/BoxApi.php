<?php

namespace Kaswell\BoxApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class BoxApi
 * @package Kaswell\BoxApi\Facades
 */
class BoxApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'BoxApi';
    }
}
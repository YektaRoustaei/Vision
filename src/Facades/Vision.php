<?php

namespace LaravelVision\Facades;

use Illuminate\Support\Facades\Facade;

class Vision extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vision';
    }
}



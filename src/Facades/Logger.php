<?php

namespace LaravelLiberu\ActivityLog\Facades;

use Illuminate\Support\Facades\Facade;

class Logger extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'logger';
    }
}

<?php

namespace LaravelLiberu\ActivityLog;

use LaravelLiberu\ActivityLog\Enums\Events;
use LaravelLiberu\Enums\EnumServiceProvider as ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    public $register = [
        'loggableEvents' => Events::class,
    ];
}

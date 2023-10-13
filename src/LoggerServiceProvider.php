<?php

namespace LaravelLiberu\ActivityLog;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use LaravelLiberu\ActivityLog\Facades\Logger;

class LoggerServiceProvider extends ServiceProvider
{
    public $register = [];

    public function boot()
    {
        if (! App::environment('testing')) {
            Logger::register($this->register);
        }
    }
}

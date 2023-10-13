<?php

namespace LaravelLiberu\ActivityLog\Traits;

use Illuminate\Database\Eloquent\Model;
use LaravelLiberu\ActivityLog\Facades\Logger;
use LaravelLiberu\ActivityLog\Services\Config;

trait IsLoggable
{
    public function model(): Model
    {
        return $this->model;
    }

    public function config(): Config
    {
        return Logger::config($this->model);
    }
}

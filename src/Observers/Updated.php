<?php

namespace LaravelLiberu\ActivityLog\Observers;

use LaravelLiberu\ActivityLog\Events\Updated as Event;
use LaravelLiberu\ActivityLog\Services\Factory;

class Updated
{
    public function updated($model)
    {
        (new Factory(new Event($model)))->create();
    }
}

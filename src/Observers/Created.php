<?php

namespace LaravelLiberu\ActivityLog\Observers;

use LaravelLiberu\ActivityLog\Events\Created as Event;
use LaravelLiberu\ActivityLog\Services\Factory;

class Created
{
    public function created($model)
    {
        (new Factory(new Event($model)))->create();
    }
}

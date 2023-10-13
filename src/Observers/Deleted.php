<?php

namespace LaravelLiberu\ActivityLog\Observers;

use LaravelLiberu\ActivityLog\Events\Deleted as Event;
use LaravelLiberu\ActivityLog\Services\Factory;

class Deleted
{
    public function deleted($model)
    {
        (new Factory(new Event($model)))->create();
    }
}

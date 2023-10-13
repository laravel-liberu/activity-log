<?php

namespace LaravelLiberu\ActivityLog\Observers;

use LaravelLiberu\ActivityLog\Events\UpdatedActiveState as Event;
use LaravelLiberu\ActivityLog\Services\Factory;
use LaravelLiberu\Helpers\Contracts\Activatable;

class ActiveState
{
    public function updatedActiveState(Activatable $model)
    {
        (new Factory(new Event($model)))->create();
    }
}

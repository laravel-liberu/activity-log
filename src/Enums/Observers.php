<?php

namespace LaravelLiberu\ActivityLog\Enums;

use LaravelLiberu\ActivityLog\Observers\ActiveState;
use LaravelLiberu\ActivityLog\Observers\Created;
use LaravelLiberu\ActivityLog\Observers\Deleted;
use LaravelLiberu\ActivityLog\Observers\Updated;
use LaravelLiberu\Enums\Services\Enum;

class Observers extends Enum
{
    protected static array $data = [
        Events::Created => Created::class,
        Events::Updated => Updated::class,
        Events::Deleted => Deleted::class,
        Events::UpdatedActiveState => ActiveState::class,
    ];
}

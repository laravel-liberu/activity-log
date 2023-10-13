<?php

namespace LaravelLiberu\ActivityLog\Events;

use Illuminate\Database\Eloquent\Model;
use LaravelLiberu\ActivityLog\Contracts\Loggable;
use LaravelLiberu\ActivityLog\Enums\Events;
use LaravelLiberu\ActivityLog\Traits\IsLoggable;

class Created implements Loggable
{
    use IsLoggable;

    public function __construct(private Model $model)
    {
    }

    public function type(): int
    {
        return Events::Created;
    }

    public function message()
    {
        return ':user created :model :label';
    }

    public function icon(): string
    {
        return 'plus';
    }

    public function iconClass(): string
    {
        return 'is-success';
    }
}

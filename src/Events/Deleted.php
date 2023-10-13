<?php

namespace LaravelLiberu\ActivityLog\Events;

use Illuminate\Database\Eloquent\Model;
use LaravelLiberu\ActivityLog\Contracts\Loggable;
use LaravelLiberu\ActivityLog\Enums\Events;
use LaravelLiberu\ActivityLog\Traits\IsLoggable;

class Deleted implements Loggable
{
    use IsLoggable;

    public function __construct(private Model $model)
    {
    }

    public function type(): int
    {
        return Events::Deleted;
    }

    public function message()
    {
        return ':user deleted :model :label';
    }

    public function icon(): string
    {
        return 'trash-alt';
    }

    public function iconClass(): string
    {
        return 'is-danger';
    }
}

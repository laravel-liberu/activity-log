<?php

namespace LaravelLiberu\ActivityLog\Events;

use LaravelLiberu\ActivityLog\Contracts\Loggable;
use LaravelLiberu\ActivityLog\Contracts\ProvidesAttributes;
use LaravelLiberu\ActivityLog\Enums\Events;
use LaravelLiberu\ActivityLog\Traits\IsLoggable;
use LaravelLiberu\Helpers\Contracts\Activatable;

class UpdatedActiveState implements Loggable, ProvidesAttributes
{
    use IsLoggable;

    public function __construct(private Activatable $model)
    {
    }

    public function type(): int
    {
        return Events::UpdatedActiveState;
    }

    public function message()
    {
        return ':user :state :model :label';
    }

    public function icon(): string
    {
        return $this->model->isActive() ? 'check' : 'ban';
    }

    public function iconClass(): string
    {
        return $this->model->isActive() ? 'is-success' : 'is-danger';
    }

    public function attributes(): array
    {
        return ['state' => $this->model->isActive() ? 'activated' : 'deactivated'];
    }
}

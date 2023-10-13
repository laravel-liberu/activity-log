<?php

namespace LaravelLiberu\ActivityLog\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use LaravelLiberu\ActivityLog\Contracts\Loggable;
use LaravelLiberu\ActivityLog\Contracts\ProvidesAttributes;
use LaravelLiberu\ActivityLog\Enums\Events;
use LaravelLiberu\ActivityLog\Facades\Logger;
use LaravelLiberu\ActivityLog\Traits\IsLoggable;
use LaravelLiberu\Enums\Services\Enum;
use LaravelLiberu\Helpers\Services\Obj;
use ReflectionClass;

class Updated implements Loggable, ProvidesAttributes
{
    use IsLoggable;

    private Collection $loggableChanges;
    private array $attributes;

    public function __construct(private Model $model)
    {
        $this->attributes = Logger::config($model)->attributes()->toArray();
        $this->loggableChanges = $this->loggableChanges();
    }

    public function type(): int
    {
        return Events::Updated;
    }

    public function message()
    {
        $message = ':user updated :model :label';
        $changes = new Collection();
        $count = $this->loggableChanges->count();

        for ($i = 0; $i < $count; $i++) {
            $changes->push(":attribute{$i} was changed from :from{$i} to :to{$i}");
        }

        return $changes->isEmpty()
            ? $message
            : $changes->prepend('with the following changes:')
            ->prepend($message)->toArray();
    }

    public function icon(): string
    {
        return 'pencil-alt';
    }

    public function attributes(): array
    {
        $attributes = new Collection();

        $this->loggableChanges->each(fn ($attribute, $i) => $attributes
            ->put("attribute{$i}", $this->attribute($attribute))
            ->put("from{$i}", $this->parse(
                $attribute,
                $this->model->getOriginal($attribute)
            ))->put("to{$i}", $this->parse($attribute, $this->model->{$attribute})));

        return $attributes->toArray();
    }

    public function iconClass(): string
    {
        return 'is-warning';
    }

    private function attribute($attribute)
    {
        return str_replace(['_id', '_'], ['', ' '], $attribute);
    }

    private function parse($attribute, $value)
    {
        if (! isset($this->attributes[$attribute])) {
            return $value;
        }

        if ($this->attributes[$attribute] instanceof Obj) {
            return $this->readRelation($this->attributes[$attribute], $value);
        }

        if (
            class_exists($this->attributes[$attribute])
            && (new ReflectionClass($this->attributes[$attribute]))
            ->isSubclassOf(Enum::class)
        ) {
            return $this->readEnum($this->attributes[$attribute], $value);
        }
    }

    private function readRelation($relation, $value)
    {
        $class = key($relation->toArray());
        $attribute = $relation->get($class);

        return $class::find($value)?->{$attribute};
    }

    private function readEnum($enum, $value)
    {
        value($enum)::localisation(false);

        return value($enum)::get($value);
    }

    private function loggableChanges()
    {
        return Collection::wrap($this->model->getDirty())
            ->intersectByKeys($this->loggableAttributes()->flip())
            ->keys();
    }

    private function loggableAttributes()
    {
        return Collection::wrap($this->attributes)
            ->map(fn ($value, $key) => is_int($key) ? $value : $key);
    }
}

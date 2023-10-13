<?php

namespace LaravelLiberu\ActivityLog\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Collection;
use LaravelLiberu\ActivityLog\Models\ActivityLog;
use LaravelLiberu\Users\Http\Resources\User;

class Timeline implements Responsable
{
    private const Chunk = 50;

    private Collection $timeline;

    public function toResponse($request)
    {
        $this->timeline = $this->timeline($request);

        return $this->days()->map(fn ($day) => [
            'date' => $day,
            'entries' => $this->dayEntries($day)
                ->map(fn ($entry) => $this->resource($entry)),
        ]);
    }

    public function timeline($request)
    {
        $filters = json_decode($request->get('filters'));

        return ActivityLog::latest()
            ->with('createdBy.person', 'createdBy.avatar')
            ->skip($request->get('offset'))
            ->between($filters->interval->min, $filters->interval->max)
            ->forUsers($filters->userIds)
            ->forEvents($filters->events)
            ->forRoles($filters->roleIds)
            ->take(self::Chunk)
            ->get();
    }

    private function resource($entry)
    {
        return [
            'id' => $entry->id,
            'meta' => $entry->meta,
            'time' => $entry->created_at->format('H:i A'),
            'owner' => new User($entry->createdBy),
        ];
    }

    private function dayEntries($day)
    {
        return $this->timeline
            ->filter(fn ($entry) => $entry->created_at->format('Y-m-d') === $day)
            ->values();
    }

    private function days()
    {
        return $this->timeline
            ->map(fn ($entry) => $entry->created_at->format('Y-m-d'))
            ->unique()
            ->values();
    }
}

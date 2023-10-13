<?php

namespace LaravelLiberu\ActivityLog\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelLiberu\ActivityLog\Http\Responses\Timeline;

class Index extends Controller
{
    public function __invoke(Timeline $timeline)
    {
        return $timeline;
    }
}

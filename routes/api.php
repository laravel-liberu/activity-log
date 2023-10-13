<?php

use Illuminate\Support\Facades\Route;
use LaravelLiberu\ActivityLog\Http\Controllers\Index;

Route::get('api/core/activityLogs', Index::class)
    ->name('core.activityLogs.index')
    ->middleware('api', 'auth', 'core');

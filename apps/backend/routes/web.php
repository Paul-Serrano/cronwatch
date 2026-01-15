<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', function () {
    $token = request()->header('X-Health-Token');
    abort_unless($token && hash_equals($token, config('services.health.token')), 401);

    if (Cache::has('health:last_critical_at')) {
        return response()->json([
            'status' => 'fail',
            'reason' => 'recent critical error',
            'at' => Cache::get('health:last_critical_at'),
        ], 500);
    }

    return response()->json(['status' => 'ok'], 200);
});
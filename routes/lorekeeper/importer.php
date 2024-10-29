<?php

use Illuminate\Support\Facades\Cache;

Route::get('/auth/callback/import', function () {
    $auth_code = request('code');
    Cache::put('auth_code', $auth_code);
    return response("Authorization complete. You can close this window.");
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('', function () {
    return [
        'message' => 'FBIS Vending API ' . config('app.env') . ' Server',
    ];
});

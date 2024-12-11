<?php

use App\Http\Controllers\API\v1\VendingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| TRANSACTIONS Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1/transactions')->middleware('auth:sanctum')
    ->group(function () {
        Route::controller(VendingController::class)->group(function () {
            Route::post('vend-airtime', 'vendAirtime');
        });
    });

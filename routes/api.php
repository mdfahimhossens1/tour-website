<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TourApiController;

Route::middleware('apikey')->group(function () {

    Route::get('/tours', [TourApiController::class, 'index']);

    Route::get('/tour/{id}', [TourApiController::class, 'show']);

});
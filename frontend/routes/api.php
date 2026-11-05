<?php

use App\Http\Controllers\Api\Internal\FornitoreController;
use App\Http\Controllers\Api\Internal\ItinerarioController;
use App\Http\Middleware\VerifyInternalApiToken;

Route::middleware([VerifyInternalApiToken::class])->prefix('internal')->group(function () {
    Route::get('/fornitori', [FornitoreController::class, 'index']);
    Route::get('/itinerari', [ItinerarioController::class, 'index']);
    Route::get('/itinerari/{itinerario}', [ItinerarioController::class, 'show']);
    Route::post('/itinerari', [ItinerarioController::class, 'store']);
});

<?php

use App\Http\Controllers\Api\Internal\FornitoreController;
use App\Http\Middleware\VerifyInternalApiToken;

Route::middleware([VerifyInternalApiToken::class])->prefix('internal')->group(function () {
    Route::get('/fornitori', [FornitoreController::class, 'index']);
});

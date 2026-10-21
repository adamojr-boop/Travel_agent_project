<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DocumentController;

// Route::controller(DocumentController::class)
//     ->prefix('documents')
//     ->group(function(){
//         Route::get('/', 'index')->name('documents.index');
//         Route::post('/store', 'store')->name('documents.store');
//         Route::get('/download/{document}', 'download')->name('documents.download');
//         Route::delete('/{document}', 'destroy')->name('documents.destroy');
//         Route::get('/test' , 'test');
//     });

Route::controller(ChatController::class)
    ->group(function(){
        Route::get('/', 'index')->name('chat.index');
    });
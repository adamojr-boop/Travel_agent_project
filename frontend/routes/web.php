<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ItinerarioController;
use App\Http\Controllers\RegioneController;
use App\Http\Controllers\FornitoreController;

// Rotta Chat
Route::controller(ChatController::class)
    ->group(function(){
        Route::get('/', 'index')->name('chat.index');
    });

// Rotta Itinerari
Route::controller(ItinerarioController::class)
    ->prefix('itinerari')
    ->group(function(){
        Route::get('/', 'index')->name('itinerari.index');
    });

// Rotta Regioni
Route::controller(RegioneController::class)
    ->prefix('regioni')
    ->group(function(){
        Route::get('/', 'index')->name('regioni.index');
    });

// Rotta Fornitori
Route::controller(FornitoreController::class)
    ->prefix('fornitori')
    ->group(function(){
        Route::get('/', 'index')->name('fornitori.index');
    });
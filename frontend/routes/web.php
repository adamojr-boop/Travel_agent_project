<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ItinerariIndex;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ItinerarioController;
use App\Http\Controllers\RegioneController;
use App\Http\Controllers\FornitoreController;

// 1. Home / Chat
Route::controller(ChatController::class)
    ->group(function(){
        Route::get('/', 'index')->name('chat.index');
    });

// 2. Itinerari (Puntato direttamente al componente Livewire)
Route::get('/itinerari', ItinerariIndex::class)->name('itinerari.index');

// 3. Regioni
Route::controller(RegioneController::class)
    ->prefix('regioni')
    ->group(function(){
        Route::get('/', 'index')->name('regioni.index');
    });

// 4. Fornitori
Route::controller(FornitoreController::class)
    ->prefix('fornitori')
    ->group(function(){
        Route::get('/', 'index')->name('fornitori.index');
    });
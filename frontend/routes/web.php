<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ItinerariIndex;
use App\Livewire\ItinerariEdit;
use App\Livewire\ItinerariShow;
use App\Livewire\RegioniIndex;
use App\Livewire\FornitoriIndex;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FornitoreController;

// 1. Home / Chat
Route::controller(ChatController::class)
    ->group(function(){
        Route::get('/', 'index')->name('chat.index');
    });

// 2. Itinerari (Livewire)
Route::get('/itinerari', ItinerariIndex::class)->name('itinerari.index');
Route::get('/itinerari/{id}', ItinerariShow::class)->name('itinerari.show');
Route::get('/itinerari/{id}/edit', ItinerariEdit::class)->name('itinerari.edit');
// 3. Regioni (Livewire)
Route::get('/regioni', RegioniIndex::class)->name('regioni.index');

// 4. Fornitori (Livewire)
Route::get('/fornitori', FornitoriIndex::class)->name('fornitori.index');
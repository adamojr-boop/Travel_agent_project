<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Livewire\ItinerariIndex;
use App\Livewire\ItinerariEdit;
use App\Livewire\ItinerariShow;
use App\Livewire\FornitoriIndex;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FornitoreController;
use App\Livewire\Chatbot;

// 1. Home / Chat
Route::controller(ChatController::class)
    ->group(function () {
        Route::get('/', 'index')->name('chat.index');
    });
Route::get('/chat/{conversationId?}', Chatbot::class)->name('chat.index');

// Chat actions handled via routes in ChatController
Route::delete('/chat/{conversationId}', function ($conversationId) {
    $conversation = Conversation::find($conversationId);
    if ($conversation) {
        $conversation->messages()->delete();
        $conversation->delete();
    }
    return redirect()->route('chat.index');
})->name('chat.destroy');
Route::patch('/chat/{conversationId}/pin', function ($conversationId) {
    $conversation = Conversation::find($conversationId);
    if ($conversation) {
        $conversation->is_pinned = !$conversation->is_pinned;
        $conversation->save();
    }
    return back();
})->name('chat.pin');
Route::patch('/chat/{conversationId}/rename', function (Request $request, $conversationId) {
    $conversation = Conversation::find($conversationId);
    if ($conversation && $request->filled('titolo')) {
        $conversation->titolo = $request->input('titolo');
        $conversation->save();
    }
    return back();
})->name('chat.rename');

// 2. Itinerari (Livewire)
Route::get('/itinerari', ItinerariIndex::class)->name('itinerari.index');
Route::get('/itinerari/{id}', ItinerariShow::class)->name('itinerari.show');
Route::get('/itinerari/{id}/edit', ItinerariEdit::class)->name('itinerari.edit');

// 3. Fornitori (Livewire)
Route::get('/fornitori', FornitoriIndex::class)->name('fornitori.index');

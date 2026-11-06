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
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', fn () => redirect()->route('chat.index'));
Route::get('/chat/{conversationId?}', Chatbot::class)->name('chat.index');

// Chat actions handled via routes in ChatController
Route::delete('/chat/{conversationId}', function ($conversationId) {
    $conversation = Conversation::where('user_id', auth()->id())->findOrFail($conversationId);
    if ($conversation) {
        $conversation->messages()->delete();
        $conversation->delete();
    }
    return redirect()->route('chat.index');
})->name('chat.destroy');
Route::patch('/chat/{conversationId}/pin', function ($conversationId) {
    $conversation = Conversation::where('user_id', auth()->id())->findOrFail($conversationId);
    if ($conversation) {
        $conversation->is_pinned = !$conversation->is_pinned;
        $conversation->save();
    }
    return back();
})->name('chat.pin');
Route::patch('/chat/{conversationId}/rename', function (Request $request, $conversationId) {
    $conversation = Conversation::where('user_id', auth()->id())->findOrFail($conversationId);
    if ($conversation && $request->filled('titolo')) {
        $conversation->titolo = $request->input('titolo');
        $conversation->save();
    }
    return back();
})->name('chat.rename');

// 2. Itinerari (Livewire)
Route::get('/itinerari', ItinerariIndex::class)->name('itinerari.index');
Route::get('/itinerari/{id}/pdf', \App\Http\Controllers\ItinerarioPdfController::class)->whereNumber('id')->name('itinerari.pdf');
    Route::get('/itinerari/{id}', ItinerariShow::class)->name('itinerari.show');
Route::get('/itinerari/{id}/edit', ItinerariEdit::class)->name('itinerari.edit');

// 3. Fornitori (Livewire)
Route::get('/fornitori', FornitoriIndex::class)->name('fornitori.index');
});

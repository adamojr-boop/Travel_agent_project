<?php

namespace Tests\Feature;

use App\Livewire\Chatbot;
use App\Livewire\ChatHistory;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ChatHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_cronologia_si_aggiorna_dopo_la_creazione_e_la_modifica_del_titolo(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $original = Conversation::create(['titolo' => 'Chat precedente', 'user_id' => $user->id]);
        Conversation::create(['titolo' => 'Chat privata', 'user_id' => User::factory()->create()->id]);

        $history = Livewire::test(ChatHistory::class, ['conversationId' => $original->id])
            ->assertSee('Chat precedente')
            ->assertDontSee('Chat privata');

        $chat = Livewire::test(Chatbot::class, ['conversationId' => $original->id])
            ->call('newConversation');
        $newId = $chat->get('conversationId');
        $chat->assertDispatched('chat-history-updated', conversationId: $newId);

        $history->dispatch('chat-history-updated', conversationId: $newId)
            ->assertSee('Nuova Conversazione')
            ->assertSee(route('chat.index', ['conversationId' => $newId]))
            ->assertSet('conversationId', $newId)
            ->assertDontSee('Chat privata');

        Livewire::test(Chatbot::class)->assertSet('conversationId', $newId);

        $chat->set('conversationTitle', 'Viaggio in Italia')
            ->call('saveTitle')
            ->assertDispatched('chat-history-updated', conversationId: $newId);
        $history->dispatch('chat-history-updated', conversationId: $newId)
            ->assertSee('Viaggio in Italia')
            ->assertDontSee('Nuova Conversazione');
    }

    public function test_la_pagina_mostra_la_prima_chat_nella_cronologia(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/chat')->assertOk()->assertSee('Nuova Conversazione');
        $conversation = Conversation::where('user_id', auth()->id())->firstOrFail();
        $this->get('/chat')->assertSee(route('chat.index', ['conversationId' => $conversation->id]));
    }

    public function test_la_cronologia_mantiene_pin_e_limite_delle_chat_recenti(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $pinned = Conversation::create(['titolo' => 'Chat fissata', 'user_id' => $user->id]);
        $pinned->is_pinned = true;
        $pinned->save();
        for ($i = 1; $i <= 8; $i++) {
            Conversation::create(['titolo' => 'Chat '.$i, 'user_id' => $user->id]);
        }

        Livewire::test(ChatHistory::class)
            ->assertSeeInOrder(['Chat fissata', 'Chat 8', 'Chat 7'])
            ->assertDontSee('Chat 1');
    }
}

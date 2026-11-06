<?php

namespace App\Livewire;

use App\Models\Conversation;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatHistory extends Component
{
    #[Locked]
    public $conversationId;

    #[On('chat-history-updated')]
    public function refreshHistory($conversationId): void
    {
        $this->conversationId = $conversationId;
    }

    public function render()
    {
        return view('livewire.chat-history', [
            'recentConversations' => Conversation::where('user_id', auth()->id())
                ->orderByDesc('is_pinned')
                ->latest()
                ->orderByDesc('id')
                ->take(8)
                ->get(),
        ]);
    }
}

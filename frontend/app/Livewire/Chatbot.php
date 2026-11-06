<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
use App\Support\AgentIdentity;

class Chatbot extends Component
{
    public $currentMessage = '';
    public $userPrompt = '';
    public $chatMessages = [];
    #[Locked]
    public $conversationId;
    public $conversationTitle = '';
    public $isEditingTitle = false;

    protected $rules = [
        'currentMessage' => 'required'
    ];

    protected $messages = [
        'currentMessage.required' => 'Please enter a message'
    ];

    public function mount($conversationId = null)
    {
        if ($conversationId) {
            $conversation = Conversation::where('user_id', auth()->id())->findOrFail($conversationId);
        } else {
            $conversation = Conversation::where('user_id', auth()->id())->latest()->orderByDesc('id')->first();
        }

        if (!$conversation) {
            $conversation = Conversation::create([
                'titolo' => 'Nuova Conversazione', 'user_id' => auth()->id(),
            ]);
        }

        $this->loadConversation($conversation);
    }

    public function loadConversation(Conversation $conversation)
    {
        abort_unless($conversation->user_id === auth()->id(), 404);
        $this->conversationId = $conversation->id;
        $this->conversationTitle = $conversation->titolo ?? 'Nuova Conversazione';
        $this->chatMessages = [];

        $savedMessages = Message::where('conversation_id', $this->conversationId)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($savedMessages as $msg) {
            $this->chatMessages[] = [
                'type' => $msg->role,
                'content' => $msg->content
            ];
        }
    }

    public function newConversation()
    {
        $conversation = Conversation::create([
            'titolo' => 'Nuova Conversazione', 'user_id' => auth()->id(),
        ]);

        $this->loadConversation($conversation);
        $this->dispatch('chat-history-updated', conversationId: $conversation->id);
    }

    public function clearCurrentChat()
    {
        $this->ownedConversation();
        Message::where('conversation_id', $this->conversationId)->delete();
        $this->chatMessages = [];
        session()->flash('message', 'Chat svuotata!');
    }

    public function startEditingTitle()
    {
        $this->isEditingTitle = true;
    }

    public function saveTitle()
    {
        $this->ownedConversation();
        if (!empty($this->conversationTitle)) {
            Conversation::where('id', $this->conversationId)->update([
                'titolo' => $this->conversationTitle
            ]);
            $this->dispatch('chat-history-updated', conversationId: $this->conversationId);
        }
        $this->isEditingTitle = false;
    }

    public function ask()
    {
        $this->ownedConversation();
        $this->validate();

        $userText = $this->currentMessage;

        $this->chatMessages[] = [
            'type' => 'human',
            'content' => $userText
        ];

        Message::create([
            'conversation_id' => $this->conversationId,
            'role' => 'human',
            'content' => $userText
        ]);

        $this->userPrompt = $userText;
        $this->currentMessage = '';

        $this->js('$wire.generateResponse');
    }

    public function generateResponse()
    {
        $this->ownedConversation();
        $fastApiUrl = config('services.fastapi.url', env('FASTAPI_URL', 'http://127.0.0.1:8000'));

        $response = Http::timeout(120)
            ->withOptions(['stream' => true])
            ->withHeaders(['X-Agent-Identity' => AgentIdentity::issue(auth()->id())])
            ->post($fastApiUrl . "/chat/travel-agent/stream", [
                'messages' => $this->chatMessages
            ]);
        $response->throw();

        $body = $response->toPsrResponse()->getBody();
        $full = '';
        $pending = '';

        while (!$body->eof()) {
            $pending .= $body->read(16);

            $len = strlen($pending);
            while ($len > 0 && !mb_check_encoding(substr($pending, 0, $len), 'UTF-8')) {
                $len--;
            }
            if ($len === 0) {
                continue;
            }

            $piece = substr($pending, 0, $len);
            $pending = substr($pending, $len);
            $full .= $piece;
            $this->stream(to: 'answer', content: $piece);
        }

        $full .= $pending;

        $this->chatMessages[] = [
            'type' => 'ai',
            'content' => $full
        ];

        Message::create([
            'conversation_id' => $this->conversationId,
            'role' => 'ai',
            'content' => $full
        ]);
    }

    public function render()
    {
        $this->dispatch('scrollChatToBottom');
        
        return view('livewire.chatbot')->layout('components.layout');
    }

    private function ownedConversation(): Conversation
    {
        return Conversation::where('user_id', auth()->id())->findOrFail($this->conversationId);
    }
}
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Http;

class Chatbot extends Component
{
    public $currentMessage = '';
    public $userPrompt = '';
    public $chatMessages = [];
    public $conversationId;

    protected $rules = [
        'currentMessage' => 'required'
    ];

    protected $messages = [
        'currentMessage.required' => 'Please enter a message'
    ];

    public function mount()
    {
        // 1. Recupera l'ultima conversazione attiva o ne crea una nuova
        $conversation = Conversation::latest()->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'titolo' => 'Nuova Conversazione Travel Planner'
            ]);
        }

        $this->conversationId = $conversation->id;

        // 2. Carica i messaggi salvati nel database per ripristinare la cronologia
        $savedMessages = Message::where('conversation_id', $this->conversationId)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($savedMessages as $msg) {
            $this->chatMessages[] = [
                'type' => $msg->role, // 'human' oppure 'ai'
                'content' => $msg->content
            ];
        }
    }

    public function ask()
    {
        $this->validate();

        $userText = $this->currentMessage;

        // Aggiunge alla vista locale
        $this->chatMessages[] = [
            'type' => 'human',
            'content' => $userText
        ];

        // Salva il messaggio dell'utente nel DB
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
        $fastApiUrl = config('services.fastapi.url', env('FASTAPI_URL', 'http://127.0.0.1:8000'));

        $response = Http::timeout(120)
            ->withOptions(['stream' => true])
            ->post($fastApiUrl . "/chat/travel-agent/stream", [
                'messages' => $this->chatMessages
            ]);

        $body = $response->toPsrResponse()->getBody();
        $full = '';
        $pending = '';

        while (!$body->eof()) {
            $pending .= $body->read(16);

            // Invia solo byte UTF-8 completi: un carattere può arrivare spezzato
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

        // Aggiunge la risposta completa alla vista locale
        $this->chatMessages[] = [
            'type' => 'ai',
            'content' => $full
        ];

        // Salva la risposta dell'assistente nel DB
        Message::create([
            'conversation_id' => $this->conversationId,
            'role' => 'ai',
            'content' => $full
        ]);
    }

    public function render()
    {
        $this->dispatch('scrollChatToBottom');
        return view('livewire.chatbot');
    }
}
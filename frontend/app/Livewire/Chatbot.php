<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Chatbot extends Component
{

    public $currentMessage = '';
    public $userPrompt = '';
    public $chatMessages = [];

    protected $rules = [
        'currentMessage' => 'required'
    ];

    protected $messages = [
        'currentMessage.required' => 'Please enter a message'
    ];

    public function ask()
    {
        $this->validate();

        $this->chatMessages[] = [
            'type' => 'human',
            'content' => $this->currentMessage
        ];

        $this->userPrompt = $this->currentMessage;

        $this->currentMessage = '';

        $this->js('$wire.generateResponse');
    }


    public function generateResponse()
    {
        $response = Http::timeout(120)
            ->withOptions(['stream' => true])
            ->post(env('FASTAPI_URL') . "/chat/travel-agent/stream", [
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

        $this->chatMessages[] = [
            'type' => 'ai',
            'content' => $full
        ];
    }

    public function render()
    {
        $this->dispatch('scrollChatToBottom');
        return view('livewire.chatbot');
    }
}

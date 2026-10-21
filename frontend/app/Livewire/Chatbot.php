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


    public function generateResponse(){

        $response = Http::timeout(120)->post(env('FASTAPI_URL') . "/chat/travel-agent" , [
            'messages' => $this->chatMessages
        ]);


        $content = $response->json();

        if(!isset($content))
        {
            dd($content);
        }

        // $messages = collect($content['messages']);

        // $this->chatMessages = $messages->filter(function($message){
        //     return $message['type'] !== 'system';
        // })->toArray();

        $this->chatMessages = $content;


    }

    public function render()
    {
        $this->dispatch('scrollChatToBottom');
        return view('livewire.chatbot');
    }
}

<div class="chat-container">
    <!-- Header/Top-Bar della Chat -->
    <div class="chat-header my-2 d-flex align-items-center justify-content-between pe-2">
        <div class="d-flex align-items-center gap-2">
            @if($isEditingTitle)
                <input type="text" wire:model.defer="conversationTitle" class="form-control form-control-sm" style="border-color: var(--lilac-ash);">
                <button wire:click="saveTitle" class="btn btn-sm btn-dark px-2 py-1"><i class="bi bi-check-lg"></i></button>
            @else
                <h2 class="fs-5 fw-bold mb-0 text-truncate" style="color: var(--text-dark);">
                    {{ $conversationTitle ?? 'Nuova Conversazione' }}
                </h2>
                <button wire:click="startEditingTitle" class="btn btn-sm text-muted p-0 ms-1" title="Rinomina chat">
                    <i class="bi bi-pencil"></i>
                </button>
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Pulsante Nuova Chat -->
            <button wire:click="newConversation" class="btn btn-sm btn-outline-dark fw-bold px-3" style="border-color: var(--lilac-ash);" title="Avvia una nuova chat">
                <i class="bi bi-plus-lg"></i> Nuova Chat
            </button>
            
            <!-- Pulsante Svuota Chat Corrente -->
            <button wire:click="clearCurrentChat" onclick="confirm('Sei sicura di voler svuotare i messaggi di questa chat?') || event.stopImmediatePropagation()" class="btn btn-sm btn-outline-danger px-2" title="Svuota messaggi chat">
                <i class="bi bi-trash"></i> Svuota
            </button>
        </div>
    </div>

    <div class="chat-box mt-2 mt-md-0 rounded-top-3" style="border: 1px solid var(--lilac-ash);">
        @forelse ($chatMessages as $key => $chatMessage)
            @if($chatMessage['type'] == 'human')
            <div wire:key="{{ $key }}" class="chat-message sent">
                <div class="chat-message-avatar">
                    <img src="/user.png" alt="Utente">
                </div>
                <div>
                    <p>{{ $chatMessage['content'] }}</p>
                </div>
            </div>
            @endif

            @if($chatMessage['type'] == 'ai' && $chatMessage['content'])
            <div wire:key="{{ $key }}" class="chat-message received">
                <div class="chat-message-avatar">
                    <img src="/RagsAI-LOGO.png" alt="RagsAI">
                </div>
                <div>
                    <x-markdown>{{ $chatMessage['content'] }}</x-markdown>
                </div>
            </div>
            @endif
        @empty
            <!-- Stile ripristinato come in origine per la chat vuota -->
            <div class="chat-message">
                <div>
                    <p class="fs-3">What do you need today?</p>
                </div>
            </div>
        @endforelse 

        @if($userPrompt)
        <div wire:loading.flex wire:target="generateResponse" class="chat-message received">
            <div class="chat-message-avatar">
                <img src="/RagsAI-LOGO.png" alt="RagsAI">
            </div>
            <div>
                <p wire:stream="answer" style="white-space: pre-wrap;"></p>
            </div>
        </div>
        @endif
    </div>

    <form id="submitForm" wire:submit.prevent="ask" class="chat-input py-3 align-items-center rounded-bottom-3" style="border: 1px solid var(--lilac-ash);">
        <div class="flex-grow-1">
            <div id="inputWrapper" class="w-100">
                <input class="messageInput" placeholder="Send a message to start the conversation..." wire:model.live="currentMessage" type="text">
                @error('currentMessage') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <button type="submit" class="btn messageBtn">
            <i class="bi bi-send"></i>
        </button>
    </form>
</div>

@script
<script>
const chatBox = document.querySelector('.chat-box');

$wire.on('scrollChatToBottom', () => {
    setTimeout(() => {
        if(chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }, 200);
});
</script>
@endscript
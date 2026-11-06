<div class="chat-container">
    <!-- Header/Top-Bar della Chat -->
    <div class="chat-header mt-2 d-flex align-items-center justify-content-between gap-2">
        <div class="chat-title d-flex align-items-center gap-2">
            @if($isEditingTitle)
                <input type="text" wire:model.defer="conversationTitle" class="form-control form-control-sm" style="border-color: var(--lilac-ash);">
                <button wire:click="saveTitle" class="btn btn-sm btn-dark px-2 py-1"><i class="bi bi-check-lg"></i></button>
            @else
                <h2 class="fs-5 fw-bold mb-0 text-truncate" style="color: var(--text-dark);">
                    {{ $conversationTitle ?? 'Nuova Conversazione' }}
                </h2>
                <button wire:click="startEditingTitle" class="btn btn-sm text-muted p-0 ms-1" title="Rinomina chat" aria-label="Rinomina chat">
                    <i class="bi bi-pencil"></i>
                </button>
            @endif
        </div>

        <div class="dropdown flex-shrink-0">
            <button class="btn btn-sm chat-menu-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Azioni chat" title="Azioni chat">
                <i class="bi bi-three-dots-vertical" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><button type="button" wire:click="newConversation" class="dropdown-item"><i class="bi bi-plus-lg me-2"></i>Nuova chat</button></li>
                <li><button type="button" wire:click="clearCurrentChat" wire:confirm="Sei sicura di voler svuotare i messaggi di questa chat?" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Svuota chat</button></li>
            </ul>
        </div>
    </div>

    <div class="chat-box" style="border: 1px solid var(--lilac-ash);">
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
            <div class="chat-message sent">
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
<div class="chat-container">
    <div class="chat-header my-3 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between pe-2">
        {{-- <h2 class="fs-4 text-truncate">{{ $chatTitle ?? '' }}</h2> --}}
    </div>
    <div class="chat-box mt-3 mt-md-0">
        @forelse ($chatMessages as $key => $chatMessage)
            @if($chatMessage['type'] == 'human')
            <div wire:key="{{ $key }}" class="chat-message sent">
                <div class="chat-message-avatar">
                    <img src="/user.png" alt="">
                </div>
                
                <div>
                    <p>{{ $chatMessage['content'] }}</p>
                </div>
            </div>
            @endif

            @if($chatMessage['type'] == 'ai' && $chatMessage['content'])
            <div wire:key="{{ $key }}" class="chat-message ">
                <div class="chat-message-avatar">
                    <img src="/RagsAI-LOGO.png" alt="Avatar">
                </div>
                
                <div>
                    <x-markdown>{{ $chatMessage['content'] }}</x-markdown>
                </div>
            </div>
            @endif
        @empty
            <div class="chat-message">
                <div>
                    <p class="fs-3">What do you need today?</p>
                </div>
            </div>
        @endforelse 
    </div>
    <form id="submitForm" wire:submit.prevent="ask" class="chat-input py-3 align-items-center">
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
        chatBox.scrollTop = chatBox.scrollHeight;
    }, 500);
});
</script>
@endscript
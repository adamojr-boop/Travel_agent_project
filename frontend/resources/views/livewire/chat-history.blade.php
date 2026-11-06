<div>
    @if($recentConversations->count() > 0)
    <ul class="nav flex-column ps-2 mt-1 border-start w-100" style="border-color: var(--lilac-ash) !important; max-height: 220px; overflow-y: auto;">
        @foreach($recentConversations as $conv)
        <li class="nav-item w-100 py-1" wire:key="conversation-{{ $conv->id }}">
            <div class="d-flex align-items-center justify-content-between pe-1 group-hover-container"
                onmouseover="this.querySelector('.chat-actions').style.display='flex';"
                onmouseout="this.querySelector('.chat-actions').style.display='none';">
                <a href="{{ route('chat.index', ['conversationId' => $conv->id]) }}"
                    class="chat-history-title text-truncate flex-grow-1 text-decoration-none {{ $conversationId == $conv->id ? 'fw-bold' : '' }}"
                    style="font-size: 0.78rem;"
                    title="{{ $conv->titolo }}">
                    <i class="bi {{ $conv->is_pinned ? 'bi-pin-fill text-primary' : 'bi-chat-left-text' }} me-1"></i> {{ $conv->titolo }}
                </a>
                <div class="chat-actions align-items-center gap-1 ms-1" style="display: none; font-size: 0.75rem; flex-shrink: 0;">
                    <form action="{{ route('chat.pin', $conv->id) }}" method="POST" class="m-0 d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm p-0 chat-history-action" title="{{ $conv->is_pinned ? 'Togli pin' : 'Fissa' }}">
                            <i class="bi {{ $conv->is_pinned ? 'bi-pin-angle' : 'bi-pin-fill' }}"></i>
                        </button>
                    </form>
                    <button type="button" class="btn btn-sm p-0 chat-history-action" title="Rinomina" onclick="let nuovoNome = prompt('Inserisci il nuovo nome:', '{{ $conv->titolo }}'); if(nuovoNome) { document.getElementById('rename-form-{{ $conv->id }}-input').value = nuovoNome; document.getElementById('rename-form-{{ $conv->id }}').submit(); }">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form id="rename-form-{{ $conv->id }}" action="{{ route('chat.rename', $conv->id) }}" method="POST" class="d-none">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="titolo" id="rename-form-{{ $conv->id }}-input">
                    </form>
                    <form action="{{ route('chat.destroy', $conv->id) }}" method="POST" class="m-0 d-inline" onsubmit="return confirm('Eliminare questa chat?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm p-0 text-danger" title="Elimina">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
    @else
    <div class="ps-3 mt-1 small chat-history-title" style="font-size: 0.75rem;">Nessuna chat recente</div>
    @endif
</div>

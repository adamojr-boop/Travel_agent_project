<div class="d-flex align-items-center">
  <button class="btn d-sm-none ms-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasResponsive" aria-controls="offcanvasResponsive">
    <i class="bi bi-list fs-1" style="color: var(--text-dark);"></i>
  </button>
</div>

<div class="offcanvas-sm offcanvas-start sidebar-offcanvas" tabindex="-1" id="offcanvasResponsive">
  <div class="offcanvas-header">
    <h1 class="fs-3 mb-0">Planner Helper</h1>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasResponsive" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="d-flex flex-column flex-shrink-0 sidebar rounded-top-3">
      <a href="{{ route('chat.index') }}" class="d-none d-sm-flex align-items-center gap-3 mb-0 link-body-emphasis text-decoration-none">
        <img class="logo" src="/RagsAI-LOGO.png" alt="">
        <h1 class="fs-3">Planner Helper</h1>
      </a>
      <hr>
      <ul class="nav nav-pills flex-column mb-auto w-100">
        <li class="mb-1 w-100">
          <div class="d-flex align-items-center justify-content-between nav-link {{ Route::currentRouteName() == 'chat.index' ? 'active' : '' }}" style="padding: 0;">
            <a href="{{ route('chat.index') }}" class="nav-link flex-grow-1 {{ Route::currentRouteName() == 'chat.index' ? 'active' : '' }}" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
              <i class="bi bi-chat"></i>
              Chat
            </a>
            <button class="btn btn-sm text-dark px-2 py-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#chatHistoryCollapse" aria-expanded="false" aria-controls="chatHistoryCollapse">
              <i class="bi bi-chevron-down small"></i>
            </button>
          </div>

          <!-- Sotto-lista a tendina contenuta nella sidebar -->
          @php
          $recentConversations = \App\Models\Conversation::orderBy('is_pinned', 'desc')->latest()->take(8)->get();
          @endphp

          <div class="collapse {{ request()->route('conversationId') ? 'show' : '' }} w-100" id="chatHistoryCollapse">
            @if($recentConversations->count() > 0)
            <ul class="nav flex-column ps-2 mt-1 border-start w-100" style="border-color: var(--lilac-ash) !important; max-height: 220px; overflow-y: auto;">
              @foreach($recentConversations as $conv)
              <li class="nav-item w-100 py-1">
                <!-- Contenitore con effetto hover: i pulsanti appaiono solo al passaggio del mouse -->
                <div class="d-flex align-items-center justify-content-between pe-1 group-hover-container"
                  onmouseover="this.querySelector('.chat-actions').style.display='flex';"
                  onmouseout="this.querySelector('.chat-actions').style.display='none';">

                  <!-- Titolo Chat (Cliccabile) -->
                  <a href="{{ route('chat.index', ['conversationId' => $conv->id]) }}"
                    class="text-truncate flex-grow-1 text-decoration-none {{ request()->route('conversationId') == $conv->id ? 'fw-bold text-dark' : 'text-muted' }}"
                    style="font-size: 0.78rem;"
                    title="{{ $conv->titolo }}">
                    <i class="bi {{ $conv->is_pinned ? 'bi-pin-fill text-primary' : 'bi-chat-left-text' }} me-1"></i> {{ $conv->titolo }}
                  </a>

                  <!-- Pulsanti Azione Rapida nascosti di default, appaiono in hover -->
                  <div class="chat-actions align-items-center gap-1 ms-1" style="display: none; font-size: 0.75rem; flex-shrink: 0;">
                    <!-- Pin -->
                    <form action="{{ route('chat.pin', $conv->id) }}" method="POST" class="m-0 d-inline">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="btn btn-sm p-0 text-muted" title="{{ $conv->is_pinned ? 'Togli pin' : 'Fissa' }}">
                        <i class="bi {{ $conv->is_pinned ? 'bi-pin-angle' : 'bi-pin-fill' }}"></i>
                      </button>
                    </form>

                    <!-- Rinomina -->
                    <button type="button" class="btn btn-sm p-0 text-muted" title="Rinomina" onclick="let nuovoNome = prompt('Inserisci il nuovo nome:', '{{ $conv->titolo }}'); if(nuovoNome) { document.getElementById('rename-form-{{ $conv->id }}-input').value = nuovoNome; document.getElementById('rename-form-{{ $conv->id }}').submit(); }">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <form id="rename-form-{{ $conv->id }}" action="{{ route('chat.rename', $conv->id) }}" method="POST" class="d-none">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="titolo" id="rename-form-{{ $conv->id }}-input">
                    </form>

                    <!-- Elimina -->
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
            <div class="ps-3 mt-1 small text-muted" style="font-size: 0.75rem;">Nessuna chat recente</div>
            @endif
          </div>
        </li>

        <li class="mb-1 mt-2">
          <a href="{{ route('itinerari.index') }}" class="nav-link {{ Route::currentRouteName() == 'itinerari.index' ? 'active' : '' }}">
            <i class="bi bi-signpost-split"></i>
            Itinerari
          </a>
        </li>
        <li class="mb-1">
          <a href="{{ route('fornitori.index') }}" class="nav-link {{ Route::currentRouteName() == 'fornitori.index' ? 'active' : '' }}">
            <i class="bi bi-person-plus"></i>
            Fornitori
          </a>
        </li>
      </ul>
      <hr>
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="/user.png" alt="" width="32" height="32" class="rounded-circle me-2">
          <strong>Mario Rossi</strong>
        </a>
        <ul class="dropdown-menu text-small shadow">
          <li><a class="dropdown-item" href="#">Settings</a></li>
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
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

          <div class="collapse {{ request()->route('conversationId') ? 'show' : '' }} w-100" id="chatHistoryCollapse">
            <livewire:chat-history :conversation-id="request()->route('conversationId')" />
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
          <strong>{{ auth()->user()->name }}</strong>
        </a>
        <ul class="dropdown-menu text-small shadow">
          <li><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="dropdown-item">Esci</button></form></li>
        </ul>
      </div>
    </div>
  </div>
</div>
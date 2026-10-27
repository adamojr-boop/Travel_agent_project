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
      <ul class="nav nav-pills flex-column mb-auto">
        <li class="mb-1">
          <a href="{{ route('chat.index') }}" class="nav-link {{ Route::currentRouteName() == 'chat.index' ? 'active' : '' }}">
            <i class="bi bi-chat"></i>
            Chat
          </a>
        </li>
        <li class="mb-1">
          <a href="#" class="nav-link">
            <i class="bi bi-signpost-split"></i>
            Itinerari
          </a>
        </li>
        <li class="mb-1">
          <a href="#" class="nav-link">
            <i class="bi bi-globe-europe-africa"></i>
            Regioni
          </a>
        </li>
        <li class="mb-1">
          <a href="#" class="nav-link">
            <i class="bi bi-person-plus"></i>
            Fornitori
          </a>
        </li>
        {{-- <li class="nav-item">
          <a href="{{ route('home') }}" class="nav-link link-body-emphasis {{ (Route::currentRouteName() == 'home') ? 'active' : ''}}" aria-current="page">
              <i class="bi bi-house"></i>
              Home
          </a>
        </li> --}}
        {{-- <li class="mb-3">
          <a 
            href="{{ route('chat.index') }}" 
            class="nav-link link-body-emphasis {{ (Route::currentRouteName() == 'chat.index') ? 'active' : ''}}"
          >
            <i class="bi bi-chat"></i>
            Chat
          </a>
        </li> --}}
        {{-- <li class="mb-3">
          <a 
            href="{{ route('documents.index') }}" 
            class="nav-link link-body-emphasis {{ (Route::currentRouteName() == 'documents.index') ? 'active' : ''}}"
          >
            <i class="bi bi-file-earmark-break"></i>
            Documents
          </a>
        </li> --}}
      </ul>
      <div>

      </div>
      <hr>
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="/user.png" alt="" width="32" height="32" class="rounded-circle me-2">
          <strong>Mario Rossi</strong>
        </a>
        <ul class="dropdown-menu text-small shadow">
          <li><a class="dropdown-item" href="#">Settings</a></li>
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

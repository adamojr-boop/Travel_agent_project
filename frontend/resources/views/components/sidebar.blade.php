<div class="d-flex align-items-center">
  <button class="btn d-md-none ms-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasResponsive" aria-controls="offcanvasResponsive">
    <i class="bi bi-list fs-1"></i>
  </button>
  {{-- <h1 class="mb-0 d-block d-md-none">RagsAI</h1> --}}
</div>

<div class="offcanvas-md offcanvas-start" tabindex="-1" id="offcanvasResponsive" aria-labelledby="offcanvasResponsiveLabel">
  <div class="offcanvas-header">
    <a href="{{ route('chat.index') }}" class="d-flex align-items-center gap-3 mb-0 me-md-auto link-body-emphasis text-decoration-none">
      <img class="logo" src="/RagsAI-LOGO.png" alt="">
      <h1 class="fs-3">RagsAI</h1>
    </a>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasResponsive" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="d-flex flex-column flex-shrink-0 p-md-4 sidebar">
      <a href="{{ route('chat.index') }}" class="d-none d-md-flex align-items-center gap-3 mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
          <img class="logo" src="/RagsAI-LOGO.png" alt="">
          <h1 class="fs-3">RagsAI</h1>
      </a> 
      <hr>
      <ul class="nav nav-pills flex-column mb-auto">
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



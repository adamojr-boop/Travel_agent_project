<x-auth-layout title="Registrati">
    <h1 class="h3 mb-2">Registrati</h1>
    <p class="text-muted mb-4">Crea il tuo account su Planner Helper.</p>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label fw-bold">Nome utente</label>
            <input id="name" name="name" class="form-control" value="{{ old('name') }}" autocomplete="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label fw-bold">Password</label>
            <input id="password" type="password" name="password" class="form-control" autocomplete="new-password" minlength="12" aria-describedby="password-help" required>
            <div id="password-help" class="form-text">Almeno 12 caratteri.</div>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label fw-bold">Conferma password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required>
        </div>
        <div class="mb-4">
            <label for="invite_code" class="form-label fw-bold">Codice d'invito</label>
            <input id="invite_code" type="password" name="invite_code" class="form-control" autocomplete="off" required>
        </div>
        <button type="submit" class="btn auth-submit w-100">Registrati</button>
    </form>

    <p class="auth-switch mb-0">Hai già un account? <a href="{{ route('login') }}">Accedi</a></p>
</x-auth-layout>

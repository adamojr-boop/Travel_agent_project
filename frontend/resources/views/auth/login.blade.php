<x-auth-layout title="Accedi">
    <h1 class="h3 mb-2">Accedi</h1>
    <p class="text-muted mb-4">Bentornato su Planner Helper.</p>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label fw-bold">Nome utente</label>
            <input id="name" name="name" class="form-control" value="{{ old('name') }}" autocomplete="username" required>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label fw-bold">Password</label>
            <input id="password" type="password" name="password" class="form-control" autocomplete="current-password" required>
        </div>
        <button type="submit" class="btn auth-submit w-100">Accedi</button>
    </form>

    <p class="auth-switch mb-0">Non hai un account? <a href="{{ route('register') }}">Registrati</a></p>
</x-auth-layout>

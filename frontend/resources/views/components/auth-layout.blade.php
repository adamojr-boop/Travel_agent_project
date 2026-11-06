@props(['title'])

<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - Planner Helper</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,100..1000;1,100..1000&family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=3" sizes="any">
    <link rel="icon" href="{{ asset('favicon.png') }}?v=3" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-bs-theme="light">
    <main class="auth-page">
        <div class="auth-card ragsy-card">
            <div class="auth-brand">
                <img src="{{ asset('RagsAI-LOGO.png') }}" alt="" width="40" height="40">
                <span>Planner Helper</span>
            </div>
            <div class="auth-content">
                {{ $slot }}
            </div>
        </div>
    </main>
</body>
</html>

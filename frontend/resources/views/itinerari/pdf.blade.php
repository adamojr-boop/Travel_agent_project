<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $itinerario->titolo }}</title>
    <style>@page { margin: 36px 40px; }</style>
</head>
<body>
    @include('itinerari.documento', ['itinerario' => $itinerario])
</body>
</html>

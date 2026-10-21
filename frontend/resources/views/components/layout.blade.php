<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">

    {{-- Faavicon --}}
    <link rel="icon" href="/RagsAI-LOGO.png" type="image/x-icon" />

    @vite(['resources/css/app.css' , 'resources/js/app.js'])
  </head>
  <body data-bs-theme="dark">
    <div class="d-block d-md-flex">
      
      <x-sidebar/>
        <main class="w-100 p-md-4">
            {{ $slot }}
        </main>
    </div>
   

  </body>
</html>
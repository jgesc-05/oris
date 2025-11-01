<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Oris | VitalCare IPS')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('head')
</head>
<body class="min-h-screen flex flex-col bg-neutral-50 text-neutral-900">
  {{-- Navbar público minimal --}}
  @if (View::exists('components.partials.navbar-guest'))
    <x-partials.navbar-guest />
  @endif

  {{-- Contenido: ocupa el espacio disponible --}}
  <main class="flex-1 py-8">
    <div class="container-pro">
      @yield('content')
    </div>
  </main>

  {{-- Footer común al final --}}
  @if (View::exists('components.partials.footer'))
    <x-partials.footer />
  @endif

  @stack('scripts')
</body>
</html>

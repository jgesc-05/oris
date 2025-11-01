{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title','Oris')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  @stack('head')
</head>
<body class="min-h-screen flex flex-col bg-neutral-50 text-neutral-900">

  {{-- Topbar (cada layout de rol la define con @section('topbar')) --}}
  @hasSection('topbar')
    @yield('topbar')
  @else
    {{-- Fallback público: navbar de guest si no hay topbar definido --}}
    @if (View::exists('components.partials.navbar-guest'))
      <x-partials.navbar-guest />
    @endif
  @endif

  {{-- Contenido principal --}}
  <main class="flex-1 container-pro py-6">
    @yield('content')
  </main>

  {{-- Footer común --}}
  @if (View::exists('components.partials.footer'))
    <x-partials.footer />
  @endif

  @stack('scripts')
</body>
</html>

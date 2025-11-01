{{-- resources/views/layouts/guest.blade.php --}}
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Oris | VitalCare IPS')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('head')
</head>
<body class="bg-neutral-50 text-neutral-900">
  {{-- Navbar público minimal (el que ya hiciste) --}}
  @if (View::exists('components.partials.navbar-guest'))
    <x-partials.navbar-guest />
  @endif

  <main class="py-8">
    <div class="container-pro">
      @yield('content')
    </div>
  </main>

  @stack('scripts')
</body>
</html>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title','Oris')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-neutral-50 text-neutral-900">

  @php
    // Permite a cada vista definir sus propios elementos de navegación
    $navItems = $navItems ?? [];
    $profile  = $profile  ?? ['name' => 'Usuario', 'avatar' => null];
  @endphp

  {{-- Navbar superior reutilizable --}}
  <x-partials.navbar-top :items="$navItems" :profile="$profile" brandSubtitle="VitalCare IPS" />

  {{-- Contenido principal --}}
  <main class="flex-1 container-pro py-6">
    @yield('content')
  </main>

  {{-- Footer común --}}
  <x-partials.footer />

</body>
</html>

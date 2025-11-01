<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title','App')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-neutral-50 text-neutral-900">

  <main class="flex-1 container-pro py-6">
    @yield('content')
  </main>

  @if (View::exists('components.partials.footer'))
    <x-partials.footer />
  @endif

</body>
</html>

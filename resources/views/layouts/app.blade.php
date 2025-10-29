<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title','App')</title>
  {{-- Carga Tailwind y JS via Vite --}}
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-neutral-50 text-neutral-900">

  <main class="container-pro py-6">
    @yield('content')
  </main>

</body>
</html>

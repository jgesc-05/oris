{{-- resources/views/components/partials/navbar-guest.blade.php --}}
@props([
  'brand' => 'VitalCare IPS',
  'logoHref' => url('/'),
])

<header class="sticky top-0 z-50 w-full bg-[var(--color-primary-50)] shadow-[var(--shadow-sm)]">
  <div class="max-w-[100%]">
    <div class="container-pro h-18 flex items-center">
      <a href="{{ $logoHref }}" class="flex items-center gap-3">
        {{-- Logo con fondo rojo y texto blanco --}}
        <span class="inline-block bg-[var(--color-primary-500)] text-white font-semibold text-xl leading-none px-2 py-0.5 rounded-md">
          Oris
        </span>

        {{-- Nombre de la clínica --}}
        <span class="text-sm md:text-2xl font-semibold text-neutral-900">
          {{ $brand }}
        </span>
      </a>
    </div>
  </div>
</header>

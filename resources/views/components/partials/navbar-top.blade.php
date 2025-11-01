@props([
  'items'   => [], // Menú superior
  'profile' => ['name' => 'Usuario', 'avatar' => null],
  'brand'   => 'VitalCare IPS',
  'logoHref'=> url('/'),
  'logoSrc' => asset('images/logo.png'),
])

<header class="sticky top-0 z-50 w-full bg-[var(--color-primary-50)] shadow-[var(--shadow-sm)]">
  <div class="max-w-[100%]">
    <div class="container-pro h-18 flex items-center justify-between">
      {{-- 🔹 Logo idéntico al guest --}}
      <a href="{{ $logoHref }}" class="flex items-center gap-3">
        <img
          src="{{ $logoSrc }}"
          alt="Logo Oris"
          class="w-10 h-10 object-contain"
        />
        <span class="text-sm md:text-2xl font-semibold text-neutral-900">
          {{ $brand }}
        </span>
      </a>

      {{-- 🔹 Menú de navegación centrado (ocupa espacio central) --}}
      <nav class="absolute left-1/2 -translate-x-1/2 hidden md:flex items-center gap-6">
        @foreach($items as $item)
          @php
            $isActive = $item['active'] ?? (url()->current() === $item['href']);
          @endphp
          <a href="{{ $item['href'] }}"
             class="text-sm font-medium transition-colors duration-150
                    {{ $isActive ? 'text-neutral-900 border-b-2 border-primary-600 pb-1' : 'text-neutral-700 hover:text-neutral-900' }}">
            {{ $item['label'] }}
          </a>
        @endforeach
      </nav>

      {{-- 🔹 Perfil alineado a la derecha --}}
      <div class="flex items-center gap-3">
        @if(!empty($profile['avatar']))
          <img src="{{ $profile['avatar'] }}"
               alt="Avatar"
               class="w-8 h-8 rounded-full object-cover border border-neutral-300" />
        @else
          <span class="w-8 h-8 rounded-full bg-[var(--color-primary-500)] text-white flex items-center justify-center font-semibold text-sm">
            {{ strtoupper(substr($profile['name'] ?? 'U', 0, 1)) }}
          </span>
        @endif
        <span class="hidden sm:inline text-sm font-medium text-neutral-800">
          {{ $profile['name'] ?? 'Usuario' }}
        </span>
      </div>
    </div>
  </div>

  {{-- 🔹 Navegación móvil debajo (centrada) --}}
  @if(!empty($items))
    <div class="md:hidden border-t border-neutral-200 bg-white">
      <nav class="flex flex-wrap justify-center gap-4 py-2">
        @foreach($items as $item)
          @php
            $isActive = $item['active'] ?? (url()->current() === $item['href']);
          @endphp
          <a href="{{ $item['href'] }}"
             class="text-sm font-medium transition-colors duration-150
                    {{ $isActive ? 'text-neutral-900 border-b-b border-primary-600' : 'text-neutral-700 hover:text-neutral-900' }}">
            {{ $item['label'] }}
          </a>
        @endforeach
      </nav>
    </div>
  @endif
</header>

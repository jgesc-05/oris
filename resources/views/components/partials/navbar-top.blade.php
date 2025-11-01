@props([
  'brand' => 'Oris',
  'brandSubtitle' => 'VitalCare IPS',
  'logo' => asset('images/12.png'),
  // items: [['label'=>'Inicio','href'=>route('admin.dashboard'),'active'=>true], ...]
  'items' => [],
  'profile' => ['name' => 'Pablo', 'avatar' => null], // opcional
])

<header class="sticky top-0 z-50 w-full bg-[var(--color-primary-50)] shadow-[var(--shadow-sm)]">
  <div class="container-pro h-16 flex items-center justify-between">
    {{-- Brand --}}
    <a href="{{ url('/') }}" class="flex items-center gap-3">
      <img src="{{ $logo }}" alt="Logo" class="w-8 h-8 object-contain">
      <div class="leading-tight">
        <div class="text-base md:text-lg font-semibold text-neutral-900">Oris</div>
        <div class="text-xs text-neutral-700 -mt-0.5">{{ $brandSubtitle }}</div>
      </div>
    </a>

    {{-- Menú superior --}}
    <nav class="hidden md:flex items-center gap-6">
      @foreach($items as $it)
        @php $active = $it['active'] ?? url()->current() === $it['href']; @endphp
        <a href="{{ $it['href'] }}"
           class="text-sm font-medium {{ $active ? 'text-neutral-900' : 'text-neutral-700 hover:text-neutral-900' }}">
          {{ $it['label'] }}
        </a>
      @endforeach
    </nav>

    {{-- Acciones derecha (perfil / salir) --}}
    <div class="flex items-center gap-3">
      @if(($profile['avatar'] ?? null))
        <img src="{{ $profile['avatar'] }}" class="w-8 h-8 rounded-full" alt="Avatar">
      @endif
      <x-ui.button variant="secondary" size="sm" :href="route('login')">Salir</x-ui.button>
    </div>
  </div>
</header>

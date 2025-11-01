@props([
  'title'   => null,
  'subtitle'=> null,
])

<section {{ $attributes->merge(['class' => 'rounded-[var(--radius)] border border-neutral-200 bg-[var(--card-bg)] shadow-[var(--shadow)]']) }}>
  @if($title || $subtitle || isset($actions))
    <div class="p-4 md:p-5 border-b border-neutral-200 flex items-center justify-between gap-3">
      <div>
        @if($title) <h2 class="text-sm md:text-base font-semibold text-neutral-900">{{ $title }}</h2> @endif
        @if($subtitle) <p class="text-xs md:text-sm text-neutral-600">{{ $subtitle }}</p> @endif
      </div>
      @isset($actions) <div class="shrink-0">{{ $actions }}</div> @endisset
    </div>
  @endif
  <div class="p-4 md:p-5">
    {{ $slot }}
  </div>
  @isset($footer)
    <div class="p-3 md:p-4 border-t border-neutral-200">
      {{ $footer }}
    </div>
  @endisset
</section>

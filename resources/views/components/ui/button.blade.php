@props([
  'variant' => 'primary',   // primary | secondary | ghost | success | warning | info
  'size'    => 'md',        // sm | md | lg
  'href'    => null,        // si pasas href, renderiza <a>
  'block'   => false,       // true => w-full (útil en mobile)
])

@php
  $base = 'inline-flex items-center justify-center font-medium rounded-[var(--radius)] transition-colors duration-150 focus:outline-none';

  $sizes = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-5 py-2.5 text-base',
  ];

  $variants = [
    'primary'   => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-400 active:bg-primary-700',
    'secondary' => 'bg-white text-neutral-800 border border-neutral-300 hover:bg-neutral-50 focus:ring-2 focus:ring-neutral-300 active:bg-neutral-100',
    'ghost'     => 'bg-transparent text-neutral-800 hover:bg-neutral-100 focus:ring-2 focus:ring-neutral-300 active:bg-neutral-200',
    'success'   => 'bg-success-600 text-white hover:bg-success-700 focus:ring-2 focus:ring-success-400 active:bg-success-700',
    'warning'   => 'bg-warning-500 text-neutral-900 hover:bg-warning-600 focus:ring-2 focus:ring-warning-400 active:bg-warning-600',
    'info'      => 'bg-info-600 text-white hover:bg-info-700 focus:ring-2 focus:ring-info-400 active:bg-info-700',
  ];

  $blockClasses = $block ? 'w-full py-3 text-base' : ''; // Más alto y legible si es full-width

  $classes = implode(' ', [
    $base,
    $sizes[$size] ?? $sizes['md'],
    $variants[$variant] ?? $variants['primary'],
    $blockClasses
  ]);
@endphp

@if ($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
  </a>
@else
<button {{ $attributes->merge(['type' => $attributes->get('type', 'submit'), 'class' => $classes]) }}>
    {{ $slot }}
  </button>
@endif

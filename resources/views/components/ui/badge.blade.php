@props(['variant' => 'neutral']) {{-- neutral | primary | success | warning | info --}}
@php
  $map = [
    'neutral' => 'bg-neutral-100 text-neutral-800',
    'primary' => 'bg-primary-100 text-primary-800',
    'success' => 'bg-success-100 text-success-800',
    'warning' => 'bg-warning-100 text-warning-800',
    'info'    => 'bg-info-100 text-info-800',
  ];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs '.$map[$variant]]) }}>
  {{ $slot }}
</span>

@props(['variant' => 'info', 'title' => null])
@php
  $map = [
    'success' => 'border-success-200 bg-success-50 text-success-800',
    'warning' => 'border-warning-200 bg-warning-50 text-warning-800',
    'info'    => 'border-info-200 bg-info-50 text-info-800',
  ];
@endphp
<div {{ $attributes->merge(['class' => 'rounded-[var(--radius)] border p-3 md:p-4 '.$map[$variant]]) }}>
  @if($title) <div class="font-semibold mb-1">{{ $title }}</div> @endif
  <div class="text-sm">{{ $slot }}</div>
</div>

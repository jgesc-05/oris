@props(['estado'])
@php
    $variant = \App\Models\Appointment::badgeVariant($estado);
@endphp

<x-ui.badge :variant="$variant">
    {{ $estado }}
</x-ui.badge>

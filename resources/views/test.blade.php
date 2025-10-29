@extends('layouts.app')

@section('title', 'Prueba de colores')

@section('content')
<div class="container-pro my-8 space-y-4">
  <h1 class="text-2xl font-semibold text-primary-600">Prueba de paleta de colores</h1>

  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="p-4 text-center rounded-[var(--radius)] bg-primary-100 text-primary-800">Primary</div>
    <div class="p-4 text-center rounded-[var(--radius)] bg-success-100 text-success-800">Success</div>
    <div class="p-4 text-center rounded-[var(--radius)] bg-warning-100 text-warning-800">Warning</div>
    <div class="p-4 text-center rounded-[var(--radius)] bg-info-100 text-info-800">Info</div>
  </div>

  <div class="rounded-[var(--radius)] border border-neutral-200 bg-[var(--card-bg)] p-6 shadow-[var(--shadow)]">
    <p class="text-lg font-semibold text-neutral-800">Tarjeta de prueba</p>
    <button class="mt-3 inline-flex items-center rounded-[var(--radius)] bg-primary-600 px-4 py-2 text-white hover:bg-primary-700 focus-ring transition-base">
      Botón primario
    </button>
  </div>
</div>
@endsection

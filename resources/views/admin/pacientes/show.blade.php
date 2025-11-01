@extends('layouts.app')
@section('title', 'Paciente #'.$id.' — Admin')

@php
  $navItems = [
    ['label'=>'Inicio','href'=>route('admin.dashboard')],
    ['label'=>'Usuarios','href'=>route('admin.usuarios.index')],
    ['label'=>'Pacientes','href'=>route('admin.pacientes.index'),'active'=>true],
    ['label'=>'Reportes','href'=>route('admin.reportes.index')],
    ['label'=>'Configuración','href'=>route('admin.config')],
  ];
@endphp

@section('content')
  <x-partials.navbar-top :items="$navItems" :profile="['name'=>'Pablo']" />
  <h1 class="text-2xl font-bold mb-4">Paciente #{{ $id }}</h1>
  <x-ui.card title="Resumen" subtitle="Vista mock">
    <p class="text-sm text-neutral-700">Aquí irá el detalle del paciente y su historial.</p>
  </x-ui.card>
@endsection

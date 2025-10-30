@extends('layouts.app')
@section('title','Demo componentes')

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-ui.card title="Acciones">
      <div class="flex flex-col sm:flex-row gap-2">
        <x-ui.button>Primario</x-ui.button>
        <x-ui.button variant="secondary">Secundario</x-ui.button>
        <x-ui.button variant="ghost">Ghost</x-ui.button>
        <x-ui.button variant="success">Éxito</x-ui.button>
        <x-ui.button variant="warning">Aviso</x-ui.button>
        <x-ui.button variant="info">Info</x-ui.button>
      </div>
      <x-slot:footer>
        <x-ui.badge variant="primary">Beta</x-ui.badge>
      </x-slot:footer>
    </x-ui.card>

    <x-ui.card title="Formulario" subtitle="Responsive">
      <form class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <x-form.input name="nombre" label="Nombre" required />
        <x-form.input name="email" type="email" label="Email" />
        <x-form.select name="rol" label="Rol" class="sm:col-span-2">
          <option>Paciente</option>
          <option>Secretaria</option>
          <option>Admin</option>
        </x-form.select>
        <x-form.textarea name="nota" label="Nota" class="sm:col-span-2" rows="4" />
        <div class="sm:col-span-2">
          <x-ui.button>Guardar</x-ui.button>
          <x-ui.button variant="secondary" class="ml-2">Cancelar</x-ui.button>
        </div>
      </form>
    </x-ui.card>
  </div>

  <div class="mt-4 space-y-3">
    <x-ui.alert variant="success" title="Éxito">Se guardó correctamente.</x-ui.alert>
    <x-ui.alert variant="warning" title="Atención">Revisa los campos obligatorios.</x-ui.alert>
    <x-ui.alert variant="info" title="Info">Este es un mensaje informativo.</x-ui.alert>
  </div>
@endsection

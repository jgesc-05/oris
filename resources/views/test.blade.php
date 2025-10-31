@extends('layouts.app')
@section('title','Demo componentes')

@section('content')
  <div class="container-pro py-8">

    {{-- Grid de cards con botones y formulario --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

      <x-ui.card title="Acciones" subtitle="Diferentes variantes de botones">
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

      <x-ui.card title="Formulario Básico" subtitle="Responsive y validado">
        <form class="space-y-0">
          {{-- Input de texto simple --}}
          <x-form.input
            name="nombre"
            label="Nombre completo"
            placeholder="Juan Pérez"
            required
          />

          {{-- Input de email con hint --}}
          <x-form.input
            name="email"
            type="email"
            label="Correo electrónico"
            placeholder="juan@ejemplo.com"
            hint="Usaremos este correo para notificaciones"
          />

          {{-- Select con opciones --}}
          <x-form.select
            name="rol"
            label="Rol en el sistema"
            required
            placeholder="Elige un rol"
          >
            <option value="paciente">Paciente</option>
            <option value="secretaria">Secretaria</option>
            <option value="admin">Administrador</option>
          </x-form.select>

          {{-- Textarea --}}
          <x-form.textarea
            name="nota"
            label="Observaciones"
            rows="3"
            placeholder="Escribe cualquier nota adicional..."
          />

          {{-- Botones de acción --}}
          <div class="flex gap-2 pt-2">
            <x-ui.button type="submit">Guardar</x-ui.button>
            <x-ui.button variant="secondary">Cancelar</x-ui.button>
          </div>
        </form>
      </x-ui.card>
    </div>

    {{-- Alertas --}}
    <div class="space-y-3 mb-8">
      <x-ui.alert variant="success" title="Éxito">Se guardó correctamente.</x-ui.alert>
      <x-ui.alert variant="warning" title="Atención">Revisa los campos obligatorios.</x-ui.alert>
      <x-ui.alert variant="info" title="Info">Este es un mensaje informativo.</x-ui.alert>
    </div>

    {{-- Demo completo de formularios --}}
    <x-ui.card title="Demo completa de formularios" subtitle="Todos los tipos de campos">
      <form class="grid grid-cols-1 md:grid-cols-2 gap-x-4">

        {{-- Inputs de texto --}}
        <x-form.input
          name="username"
          label="Usuario"
          placeholder="usuario123"
          required
        />

        <x-form.input
          name="password"
          type="password"
          label="Contraseña"
          required
        />

        {{-- Números y fechas --}}
        <x-form.input
          name="edad"
          type="number"
          label="Edad"
          placeholder="25"
          hint="Debe ser mayor de 18 años"
        />

        <x-form.input
          name="fecha_nacimiento"
          type="date"
          label="Fecha de nacimiento"
        />

        {{-- Select y textarea a ancho completo --}}
        <x-form.select
          name="pais"
          label="País"
          class="md:col-span-2"
        >
          <option value="co">Colombia</option>
          <option value="mx">México</option>
          <option value="ar">Argentina</option>
        </x-form.select>

        <x-form.textarea
          name="biografia"
          label="Biografía"
          class="md:col-span-2"
          rows="4"
          placeholder="Cuéntanos sobre ti..."
        />

        {{-- Checkbox --}}
        <div class="md:col-span-2">
          <x-form.checkbox
            name="terminos"
            label="Acepto los términos y condiciones"
            hint="Debes aceptar para continuar"
          />
        </div>

        {{-- Botones --}}
        <div class="md:col-span-2 flex gap-2 pt-4">
          <x-ui.button type="submit" variant="primary">
            Registrarse
          </x-ui.button>
          <x-ui.button variant="secondary">
            Cancelar
          </x-ui.button>
        </div>
      </form>
    </x-ui.card>

  </div>
@endsection

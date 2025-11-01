@props([
  'label' => null,
  'name',
  'error' => null,
  'hint' => null,
  'required' => false,
  'placeholder' => '-- Seleccionar --',
])

<div class="form-group">
  @if($label)
    <label class="form-label" for="{{ $name }}">
      {{ $label }}
      @if($required)<span class="form-required">*</span>@endif
    </label>
  @endif

  <select
    id="{{ $name }}"
    name="{{ $name }}"
    {{ $required ? 'required' : '' }}
    {{ $attributes->merge(['class' => 'form-select']) }}
  >
    @if($placeholder)
      <option value="">{{ $placeholder }}</option>
    @endif
    {{ $slot }}
  </select>

  @if($hint)
    <p class="form-hint">{{ $hint }}</p>
  @endif

  @if($error)
    <p class="form-error">{{ $error }}</p>
  @endif
</div>

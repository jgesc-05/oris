@props([
  'label' => null,
  'name',
  'type' => 'text',
  'error' => null,
  'hint' => null,
  'required' => false,
  'placeholder' => null,
  'value' => null,
])

<div class="form-group">
  @if($label)
    <label class="form-label" for="{{ $name }}">
      {{ $label }}
      @if($required)<span class="form-required">*</span>@endif
    </label>
  @endif

  <input
    type="{{ $type }}"
    id="{{ $name }}"
    name="{{ $name }}"
    value="{{ old($name, $value) }}"
    placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }}
    {{ $attributes->merge(['class' => 'form-control']) }}
  />

  @if($hint)
    <p class="form-hint">{{ $hint }}</p>
  @endif

  @if($error)
    <p class="form-error">{{ $error }}</p>
  @endif
</div>

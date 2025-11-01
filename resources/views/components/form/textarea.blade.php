@props([
  'label' => null,
  'name',
  'rows' => 4,
  'error' => null,
  'hint' => null,
  'required' => false,
  'placeholder' => null,
])

<div class="form-group">
  @if($label)
    <label class="form-label" for="{{ $name }}">
      {{ $label }}
      @if($required)<span class="form-required">*</span>@endif
    </label>
  @endif

  <textarea
    id="{{ $name }}"
    name="{{ $name }}"
    rows="{{ $rows }}"
    placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }}
    {{ $attributes->merge(['class' => 'form-textarea']) }}
  >{{ old($name, $slot) }}</textarea>

  @if($hint)
    <p class="form-hint">{{ $hint }}</p>
  @endif

  @if($error)
    <p class="form-error">{{ $error }}</p>
  @endif
</div>

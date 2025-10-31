@props([
  'label' => null,
  'name',
  'value' => '1',
  'checked' => false,
  'error' => null,
  'hint' => null,
])

<div class="form-group">
  <div class="flex items-start gap-2">
    <input
      type="checkbox"
      id="{{ $name }}"
      name="{{ $name }}"
      value="{{ $value }}"
      {{ $checked ? 'checked' : '' }}
      {{ $attributes->merge(['class' => 'form-checkbox mt-0.5']) }}
    />

    @if($label)
      <label class="text-sm text-neutral-700 cursor-pointer" for="{{ $name }}">
        {{ $label }}
      </label>
    @endif
  </div>

  @if($hint)
    <p class="form-hint ml-6">{{ $hint }}</p>
  @endif

  @if($error)
    <p class="form-error ml-6">{{ $error }}</p>
  @endif
</div>

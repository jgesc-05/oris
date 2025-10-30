@props(['label'=>null,'name','error'=>null,'required'=>false])
<label class="block text-sm mb-1" for="{{ $name }}">
  {{ $label ?? ucfirst($name) }} @if($required)<span class="text-danger">*</span>@endif
</label>
<select id="{{ $name }}" name="{{ $name }}"
  {{ $attributes->merge(['class'=>'w-full rounded-[var(--radius)] border border-neutral-300 bg-white text-neutral-900 focus:ring-primary-500 focus:border-primary-500']) }}>
  {{ $slot }}
</select>
@isset($error) <p class="text-xs text-danger mt-1">{{ $error }}</p> @endisset

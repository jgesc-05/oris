@props(['label'=>null,'name','type'=>'text','error'=>null,'hint'=>null,'required'=>false])
<label class="block text-sm mb-1" for="{{ $name }}">
  {{ $label ?? ucfirst($name) }} @if($required)<span class="text-danger">*</span>@endif
</label>
<input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
  {{ $attributes->merge(['class'=>'w-full rounded-[var(--radius)] border border-neutral-300 bg-white text-neutral-900 placeholder-neutral-400 focus:ring-primary-500 focus:border-primary-500']) }}/>
@if($hint) <p class="text-xs text-neutral-500 mt-1">{{ $hint }}</p> @endif
@isset($error) <p class="text-xs text-danger mt-1">{{ $error }}</p> @endisset

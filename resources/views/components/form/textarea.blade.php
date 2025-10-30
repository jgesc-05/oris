@props(['label'=>null,'name','rows'=>4,'error'=>null,'required'=>false])
<label class="block text-sm mb-1" for="{{ $name }}">
  {{ $label ?? ucfirst($name) }} @if($required)<span class="text-danger">*</span>@endif
</label>
<textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}"
  {{ $attributes->merge(['class'=>'w-full rounded-[var(--radius)] border border-neutral-300 bg-white text-neutral-900 placeholder-neutral-400 focus:ring-primary-500 focus:border-primary-500']) }}>{{ $slot }}</textarea>
@isset($error) <p class="text-xs text-danger mt-1">{{ $error }}</p> @endisset

@props(['name', 'label', 'type' => 'text', 'value' => '', 'hint' => null, 'required' => false])

<div class="form-group">
    <label for="{{ $name }}" class="label">
        {{ $label }}
        @if($required)<span class="text-red-500">*</span>@endif
    </label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'input']) }}
    >
    @if($hint)<p class="hint">{{ $hint }}</p>@endif
    @error($name)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

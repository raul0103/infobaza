@props(['name', 'label', 'value' => '', 'rows' => 5, 'hint' => null, 'required' => false])

<div class="form-group">
    <label for="{{ $name }}" class="label">
        {{ $label }}
        @if($required)<span class="text-red-500">*</span>@endif
    </label>
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'textarea']) }}
    >{{ old($name, $value) }}</textarea>
    @if($hint)<p class="hint">{{ $hint }}</p>@endif
    @error($name)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

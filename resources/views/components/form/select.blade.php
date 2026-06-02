@props(['name', 'label', 'placeholder' => '— не выбрано —', 'hint' => null, 'required' => false])

<div class="form-group">
    <label for="{{ $name }}" class="label">
        {{ $label }}
        @if($required)<span class="text-red-500">*</span>@endif
    </label>
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'select']) }}
    >
        @if($placeholder !== false)
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>
    @if($hint)<p class="hint">{{ $hint }}</p>@endif
    @error($name)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

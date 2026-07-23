@props(['name', 'label', 'value' => '', 'rows' => 5, 'hint' => null, 'required' => false, 'markdown' => false])

@php
    $markdownHint = 'Markdown: **жирный**, *курсив*, списки, `код`, ссылки';
    $finalHint = $markdown
        ? trim(($hint ? $hint.' · ' : '').$markdownHint)
        : $hint;
@endphp

<div class="form-group">
    <label for="{{ $name }}" class="label">
        {{ $label }}
        @if($required)<span class="text-red-500">*</span>@endif
        @if($markdown)<span class="ml-1 text-xs font-normal text-gray-400">MD</span>@endif
    </label>
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'textarea']) }}
    >{{ old($name, $value) }}</textarea>
    @if($finalHint)<p class="hint">{{ $finalHint }}</p>@endif
    @error($name)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

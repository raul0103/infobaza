@props(['content' => null])

@php
    $source = $content ?? (string) $slot;
    $html = \App\Support\Markdown::parse($source);
@endphp

@if($html->toHtml() !== '')
    <div {{ $attributes->merge(['class' => 'markdown-body']) }}>
        {!! $html !!}
    </div>
@endif

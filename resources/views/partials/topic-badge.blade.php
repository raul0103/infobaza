@props(['topic'])

@php
    $href = route('topics.show', $topic);
@endphp

@if($topic->isChild())
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'badge-gray shrink-0 hover:bg-gray-200 transition-colors']) }}>{{ $topic->name }}</a>
@else
    @php $color = $topic->markColor(); @endphp
    <a href="{{ $href }}" {{ $attributes->merge([
        'class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium border shrink-0 hover:opacity-80 transition-opacity',
        'style' => "background-color: {$color}20; color: {$color}; border-color: {$color}40;",
    ]) }}>
        {{ $topic->name }}
    </a>
@endif

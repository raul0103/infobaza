@props(['topic'])

@if($topic->isChild())
    <span {{ $attributes->merge(['class' => 'badge-gray shrink-0']) }}>{{ $topic->name }}</span>
@else
    @php $color = $topic->markColor(); @endphp
    <span {{ $attributes->merge([
        'class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium border shrink-0',
        'style' => "background-color: {$color}20; color: {$color}; border-color: {$color}40;",
    ]) }}>
        {{ $topic->name }}
    </span>
@endif

@props(['topic', 'nested' => false])

@php
    $color = $topic->markColor();
@endphp

<div @class([
    'relative flex flex-col',
    'rounded-lg border border-gray-100 bg-gray-50/80 p-4 hover:border-gray-200 transition' => $nested,
    'card-hover' => ! $nested,
])>
    <div class="absolute right-2 top-2">
        @include('partials.item-actions', [
            'edit' => route('topics.edit', $topic),
            'destroy' => route('topics.destroy', $topic),
        ])
    </div>
    <a href="{{ route('topics.show', $topic) }}" class="flex-1 block">
        <div class="flex items-center gap-2 mb-2">
            @if($color)
                <span class="w-3 h-3 rounded-full shrink-0 bg-blue-500"></span>
            @endif
            <h3 @class(['font-semibold text-gray-900', 'text-lg' => ! $nested, 'text-base' => $nested])>{{ $topic->name }}</h3>
            @if($nested)
                <span class="badge-gray text-[10px]">подтема</span>
            @endif
        </div>
        <p class="text-sm text-gray-500">{{ $topic->notes_count }} записей</p>
    </a>
</div>

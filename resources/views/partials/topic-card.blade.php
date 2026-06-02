@props(['topic', 'nested' => false])

@php
    $color = $topic->markColor();
@endphp

<div @class([
    'flex flex-col',
    'rounded-lg border border-gray-100 bg-gray-50/80 p-4 hover:border-gray-200 transition' => $nested,
    'card-hover' => ! $nested,
])>
    <a href="{{ route('topics.show', $topic) }}" class="flex-1 block">
        <div class="flex items-center gap-2 mb-2">
            @if($color)
                <span class="w-3 h-3 rounded-full shrink-0" style="background:{{ $color }}"></span>
            @endif
            <h3 @class(['font-semibold text-gray-900', 'text-lg' => ! $nested, 'text-base' => $nested])>{{ $topic->name }}</h3>
            @if($nested)
                <span class="badge-gray text-[10px]">подтема</span>
            @endif
        </div>
        <p class="text-sm text-gray-500">{{ $topic->notes_count }} записей · {{ $topic->quotes_count }} цитат</p>
    </a>
    <div class="mt-3 pt-3 border-t border-gray-200/80">
        @include('partials.item-actions', [
            'edit' => route('topics.edit', $topic),
            'destroy' => route('topics.destroy', $topic),
        ])
    </div>
</div>

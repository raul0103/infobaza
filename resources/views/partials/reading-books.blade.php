@php
    $showActions = $showActions ?? false;
@endphp

@foreach($books as $book)
    <div class="flex items-start gap-3 mb-3 last:mb-0">
        <a href="{{ route('books.show', $book) }}" class="block flex-1 min-w-0">
            <div class="flex justify-between text-sm mb-1 gap-3">
                <span class="font-medium text-gray-900 truncate">{{ $book->title }}</span>
                <span class="text-gray-500 tabular-nums shrink-0">{{ $book->readingPercent() ?? 0 }}%</span>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width:{{ $book->readingPercent() ?? 0 }}%"></div>
            </div>
        </a>
        @if($showActions)
            @include('partials.item-actions', [
                'edit' => route('books.edit', $book),
                'destroy' => route('books.destroy', $book),
            ])
        @endif
    </div>
@endforeach

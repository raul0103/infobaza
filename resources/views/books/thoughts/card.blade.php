@php($showSource = $showSource ?? false)

<div class="card border-l-4 border-l-amber-400 p-3 sm:p-4">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0 flex-1">
            <div class="text-sm text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $thought->content }}</div>

            @if($thought->chapter || $thought->page)
                <div class="flex flex-wrap gap-2 mt-3">
                    @if($thought->chapter)<span class="badge-gray">{{ $thought->chapter }}</span>@endif
                    @if($thought->page)<span class="badge-gray">Стр. {{ $thought->page }}</span>@endif
                </div>
            @endif

            @if($showSource && $thought->book)
                <div class="mt-3 text-sm">
                    <a href="{{ route('books.show', $thought->book) }}" class="link">
                        Источник: {{ $thought->book->title }} →
                    </a>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-1 shrink-0">
            @include('partials.favorite-button', [
                'active' => $thought->is_favorite,
                'action' => route('favorites.thoughts.toggle', $thought),
                'label' => 'мысль',
            ])
            @include('partials.item-actions', [
                'edit' => route('books.thoughts.edit', [$thought->book_id, $thought]),
                'destroy' => route('books.thoughts.destroy', [$thought->book_id, $thought]),
            ])
        </div>
    </div>
</div>

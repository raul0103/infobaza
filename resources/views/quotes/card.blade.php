@php($showSource = $showSource ?? false)

<div class="card border-l-4 border-l-blue-500 p-3 sm:p-4">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0 flex-1">
            <blockquote class="text-sm text-gray-800 italic leading-relaxed whitespace-pre-wrap">«{{ $quote->text }}»</blockquote>

            @if($quote->context)
                <div class="mt-3 rounded-lg bg-blue-50/60 px-3 py-2 text-sm text-gray-600">
                    <span class="text-xs font-medium uppercase tracking-wide text-blue-500">Контекст</span>
                    <div class="mt-1 whitespace-pre-wrap">{{ $quote->context }}</div>
                </div>
            @endif

            @if($quote->page || $quote->character)
                <div class="flex flex-wrap gap-2 mt-3">
                    @if($quote->page)<span class="badge-gray">Стр. {{ $quote->page }}</span>@endif
                    @if($quote->character)<span class="badge-gray">— {{ $quote->character }}</span>@endif
                </div>
            @endif

            @if($showSource)
                <div class="mt-3 text-sm">
                    @if($quote->book)
                        <a href="{{ route('books.show', $quote->book) }}" class="link">
                            Источник: {{ $quote->book->title }} →
                        </a>
                    @elseif($quote->movie)
                        <a href="{{ route('movies.show', $quote->movie) }}" class="link">
                            Источник: {{ $quote->movie->title }} →
                        </a>
                    @else
                        <span class="text-gray-400">Источник не указан</span>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-1 shrink-0">
            @include('partials.favorite-button', [
                'active' => $quote->is_favorite,
                'action' => route('favorites.quotes.toggle', $quote),
                'label' => 'цитата',
            ])
            @include('partials.item-actions', [
                'edit' => route('quotes.edit', $quote),
                'destroy' => route('quotes.destroy', $quote),
            ])
        </div>
    </div>
</div>

@php
    $showSource = $showSource ?? false;

    $quoteSourceLabel = '';
    $quoteSourceUrl = '';
    if ($showSource && $quote->book) {
        $quoteSourceLabel = $quote->book->title;
        $quoteSourceUrl = route('books.show', $quote->book);
    } elseif ($showSource && $quote->movie) {
        $quoteSourceLabel = $quote->movie->title;
        $quoteSourceUrl = route('movies.show', $quote->movie);
    }
@endphp

<div
    class="card border-l-4 border-l-blue-500 !p-2.5 sm:!p-3 flex items-center gap-2 cursor-pointer hover:border-blue-300 transition"
    data-kind="quote"
    data-text="{{ rawurlencode($quote->text) }}"
    data-text-html="{{ rawurlencode(\App\Support\Markdown::parse($quote->text)->toHtml()) }}"
    data-context="{{ rawurlencode((string) $quote->context) }}"
    data-context-html="{{ rawurlencode(\App\Support\Markdown::parse($quote->context)->toHtml()) }}"
    data-page="{{ rawurlencode((string) $quote->page) }}"
    data-character="{{ rawurlencode((string) $quote->character) }}"
    data-source-label="{{ rawurlencode($quoteSourceLabel) }}"
    data-source-url="{{ $quoteSourceUrl }}"
    data-edit-url="{{ route('quotes.edit', $quote) }}"
    onclick="if (!event.target.closest('a, button, form')) openCardModal(this)"
>
    <p class="flex-1 min-w-0 text-sm text-gray-800 italic truncate">«{{ $quote->text }}»</p>

    @if($quoteSourceUrl)
        <a href="{{ $quoteSourceUrl }}" class="badge-blue shrink-0 max-w-[8rem] truncate hover:bg-blue-100" title="{{ $quoteSourceLabel }}">{{ $quoteSourceLabel }}</a>
    @endif

    @if($quote->page)
        <span class="badge-gray shrink-0 hidden sm:inline-flex">Стр. {{ $quote->page }}</span>
    @endif

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

@include('partials.card-detail-modal')

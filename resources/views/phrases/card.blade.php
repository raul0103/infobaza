@php
    $showSource = $showSource ?? false;

    $phraseSourceLabel = '';
    $phraseSourceUrl = '';
    if ($showSource && $phrase->book) {
        $phraseSourceLabel = $phrase->book->title;
        $phraseSourceUrl = route('books.show', $phrase->book);
    } elseif ($showSource && $phrase->movie) {
        $phraseSourceLabel = $phrase->movie->title;
        $phraseSourceUrl = route('movies.show', $phrase->movie);
    }
@endphp

<div
    class="card border-l-4 border-l-teal-500 !p-2.5 sm:!p-3 flex items-center gap-2 cursor-pointer hover:border-teal-300 transition"
    data-kind="phrase"
    data-text="{{ rawurlencode($phrase->text) }}"
    data-text-html="{{ rawurlencode(\App\Support\Markdown::parse($phrase->text)->toHtml()) }}"
    data-context="{{ rawurlencode((string) $phrase->note) }}"
    data-context-html="{{ rawurlencode(\App\Support\Markdown::parse($phrase->note)->toHtml()) }}"
    data-page="{{ rawurlencode((string) $phrase->page) }}"
    data-character="{{ rawurlencode((string) $phrase->character) }}"
    data-source-label="{{ rawurlencode($phraseSourceLabel) }}"
    data-source-url="{{ $phraseSourceUrl }}"
    data-edit-url="{{ route('phrases.edit', $phrase) }}"
    onclick="if (!event.target.closest('a, button, form')) openCardModal(this)"
>
    <p class="flex-1 min-w-0 text-sm text-gray-800 italic truncate">«{{ $phrase->text }}»</p>

    @if($phraseSourceUrl)
        <a href="{{ $phraseSourceUrl }}" class="badge-blue shrink-0 max-w-[8rem] truncate hover:bg-blue-100" title="{{ $phraseSourceLabel }}">{{ $phraseSourceLabel }}</a>
    @endif

    @if($phrase->page)
        <span class="badge-gray shrink-0 hidden sm:inline-flex">Стр. {{ $phrase->page }}</span>
    @endif

    <div class="flex items-center gap-1 shrink-0">
        @include('partials.favorite-button', [
            'active' => $phrase->is_favorite,
            'action' => route('favorites.phrases.toggle', $phrase),
            'label' => 'оборот речи',
        ])
        @include('partials.item-actions', [
            'edit' => route('phrases.edit', $phrase),
            'destroy' => route('phrases.destroy', $phrase),
        ])
    </div>
</div>

@include('partials.card-detail-modal')

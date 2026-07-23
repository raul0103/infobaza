@php
    $showSource = $showSource ?? false;

    $tipSourceLabel = '';
    $tipSourceUrl = '';
    if ($showSource && $tip->book) {
        $tipSourceLabel = $tip->book->title;
        $tipSourceUrl = route('books.show', $tip->book);
    } elseif ($showSource && $tip->movie) {
        $tipSourceLabel = $tip->movie->title;
        $tipSourceUrl = route('movies.show', $tip->movie);
    }
@endphp

<div
    class="card border-l-4 border-l-emerald-500 !p-2.5 sm:!p-3 flex items-center gap-2 cursor-pointer hover:border-emerald-300 transition"
    data-kind="tip"
    data-title="{{ rawurlencode((string) $tip->title) }}"
    data-text="{{ rawurlencode($tip->content) }}"
    data-text-html="{{ rawurlencode(\App\Support\Markdown::parse($tip->content)->toHtml()) }}"
    data-chapter="{{ rawurlencode((string) $tip->chapter) }}"
    data-page="{{ rawurlencode((string) $tip->page) }}"
    data-source-label="{{ rawurlencode($tipSourceLabel) }}"
    data-source-url="{{ $tipSourceUrl }}"
    data-edit-url="{{ route('tips.edit', $tip) }}"
    onclick="if (!event.target.closest('a, button, form')) openCardModal(this)"
>
    <div class="flex-1 min-w-0">
        @if(filled($tip->title))
            <p class="text-sm font-medium text-gray-900 truncate">{{ $tip->title }}</p>
            <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $tip->content }}</p>
        @else
            <p class="text-sm text-gray-800 truncate">{{ $tip->content }}</p>
        @endif
    </div>

    @if($tip->page)
        <span class="badge-gray shrink-0 hidden sm:inline-flex">{{ $tip->page }}</span>
    @endif

    <div class="flex items-center gap-1 shrink-0">
        @include('partials.item-actions', [
            'edit' => route('tips.edit', $tip),
            'destroy' => route('tips.destroy', $tip),
        ])
    </div>
</div>

@include('partials.card-detail-modal')

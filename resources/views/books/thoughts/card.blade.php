@php($showSource = $showSource ?? false)

<div
    class="card border-l-4 border-l-amber-400 !p-2.5 sm:!p-3 flex items-center gap-2 cursor-pointer hover:border-amber-300 transition"
    data-kind="thought"
    data-text="{{ rawurlencode($thought->content) }}"
    data-chapter="{{ rawurlencode((string) $thought->chapter) }}"
    data-page="{{ rawurlencode((string) $thought->page) }}"
    data-source-label="{{ rawurlencode($showSource && $thought->book ? $thought->book->title : '') }}"
    data-source-url="{{ $showSource && $thought->book ? route('books.show', $thought->book) : '' }}"
    onclick="if (!event.target.closest('a, button, form')) openCardModal(this)"
>
    <p class="flex-1 min-w-0 text-sm text-gray-800 truncate">{{ $thought->content }}</p>

    @if($thought->chapter || $thought->page)
        <span class="badge-gray shrink-0 hidden sm:inline-flex">
            {{ $thought->chapter ?: 'Стр. '.$thought->page }}
        </span>
    @endif

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

@include('partials.card-detail-modal')

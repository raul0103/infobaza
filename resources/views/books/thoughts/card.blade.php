@php
    $showSource = $showSource ?? false;
    $sourceLabel = $showSource ? $thought->sourceLabel() : '';
    $sourceUrl = $showSource ? $thought->sourceUrl() : null;
    if ($sourceLabel === 'Без источника') {
        $sourceLabel = '';
    }
@endphp

<div
    class="card border-l-4 border-l-amber-400 !p-2.5 sm:!p-3 flex items-center gap-2 cursor-pointer hover:border-amber-300 transition"
    data-kind="thought"
    data-text="{{ rawurlencode($thought->content) }}"
    data-text-html="{{ rawurlencode(\App\Support\Markdown::parse($thought->content)->toHtml()) }}"
    data-chapter="{{ rawurlencode((string) $thought->chapter) }}"
    data-page="{{ rawurlencode((string) $thought->page) }}"
    data-source-label="{{ rawurlencode($sourceLabel) }}"
    data-source-url="{{ $sourceUrl }}"
    data-edit-url="{{ $thought->editUrl() }}"
    onclick="if (!event.target.closest('a, button, form')) openCardModal(this)"
>
    <p class="flex-1 min-w-0 text-sm text-gray-800 truncate">{{ $thought->content }}</p>

    @if($showSource && $sourceUrl)
        <a href="{{ $sourceUrl }}" class="badge-blue shrink-0 max-w-[8rem] truncate hover:bg-blue-100" title="{{ $sourceLabel }}">{{ $sourceLabel }}</a>
    @endif

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
            'edit' => $thought->editUrl(),
            'destroy' => $thought->destroyUrl(),
        ])
    </div>
</div>

@include('partials.card-detail-modal')

<div
    class="card border-l-4 border-l-amber-500 !p-2.5 sm:!p-3 flex items-center gap-2 cursor-pointer hover:border-amber-300 transition"
    data-kind="joke"
    data-text="{{ rawurlencode($joke->text) }}"
    data-text-html="{{ rawurlencode(\App\Support\Markdown::parse($joke->text)->toHtml()) }}"
    data-source-label="{{ rawurlencode((string) $joke->source) }}"
    data-edit-url="{{ route('jokes.edit', $joke) }}"
    onclick="if (!event.target.closest('a, button, form')) openCardModal(this)"
>
    <p class="flex-1 min-w-0 text-sm text-gray-800 truncate">{{ $joke->text }}</p>

    @if($joke->source)
        <span class="badge-gray shrink-0 max-w-[8rem] truncate hidden sm:inline-flex" title="{{ $joke->source }}">{{ $joke->source }}</span>
    @endif

    <div class="flex items-center gap-1 shrink-0">
        @include('partials.item-actions', [
            'edit' => route('jokes.edit', $joke),
            'destroy' => route('jokes.destroy', $joke),
        ])
    </div>
</div>

@include('partials.card-detail-modal')

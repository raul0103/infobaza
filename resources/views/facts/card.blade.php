@php
    $preview = $fact->title ?: $fact->text;
@endphp

<div
    class="card border-l-4 border-l-violet-500 !p-2.5 sm:!p-3 flex items-center gap-2 cursor-pointer hover:border-violet-300 transition"
    data-kind="fact"
    data-title="{{ rawurlencode((string) $fact->title) }}"
    data-text="{{ rawurlencode($fact->text) }}"
    data-text-html="{{ rawurlencode(\App\Support\Markdown::parse($fact->text)->toHtml()) }}"
    data-source-label="{{ rawurlencode((string) $fact->source) }}"
    data-edit-url="{{ route('facts.edit', $fact) }}"
    onclick="if (!event.target.closest('a, button, form')) openCardModal(this)"
>
    <p class="flex-1 min-w-0 text-sm text-gray-800 truncate">{{ $preview }}</p>

    @if($fact->source)
        <span class="badge-gray shrink-0 max-w-[8rem] truncate hidden sm:inline-flex" title="{{ $fact->source }}">{{ $fact->source }}</span>
    @endif

    <div class="flex items-center gap-1 shrink-0">
        @include('partials.item-actions', [
            'edit' => route('facts.edit', $fact),
            'destroy' => route('facts.destroy', $fact),
        ])
    </div>
</div>

@include('partials.card-detail-modal')

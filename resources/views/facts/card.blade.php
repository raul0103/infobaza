<div class="card p-4 sm:p-4">
    <div class="flex items-start justify-between gap-3">
        <div class="flex-1 min-w-0">
            @if($fact->title)
                <h3 class="font-semibold text-gray-900 mb-1.5">{{ $fact->title }}</h3>
            @endif
            <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $fact->text }}</p>
            @if($fact->source)
                <p class="text-xs text-blue-600 font-medium mt-2">{{ $fact->source }}</p>
            @endif
        </div>
        @include('partials.item-actions', [
            'edit' => route('facts.edit', $fact),
            'destroy' => route('facts.destroy', $fact),
        ])
    </div>
</div>

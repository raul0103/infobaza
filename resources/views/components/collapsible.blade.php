@props([
    'title',
    'count' => null,
    'open' => false,
    'id' => null,
])

<details
    @if($id) id="{{ $id }}" @endif
    class="collapsible-section mt-6 first:mt-0"
    @if($open) open @endif
    {{ $attributes }}
>
    <summary class="flex items-center justify-between gap-3 cursor-pointer select-none rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-soft hover:border-blue-200 hover:shadow-card transition">
        <div class="flex items-center gap-2 min-w-0">
            <svg class="collapse-chevron w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <h2 class="section-title truncate">{{ $title }}</h2>
            @if($count !== null)
                <span class="badge-gray shrink-0">{{ $count }}</span>
            @endif
        </div>
        @isset($actions)
            <div class="shrink-0 flex items-center gap-2" onclick="event.preventDefault(); event.stopPropagation();">
                {{ $actions }}
            </div>
        @endisset
    </summary>

    @isset($subtitle)
        <div class="px-1 pt-2 text-sm text-gray-500 whitespace-pre-wrap">{{ $subtitle }}</div>
    @endisset

    <div class="pt-3 space-y-4">
        {{ $slot }}
    </div>
</details>

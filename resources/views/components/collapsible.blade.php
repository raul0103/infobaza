@props([
    'title',
    'count' => null,
    'open' => false,
    'id' => null,
])

<details
    @if($id) id="{{ $id }}" @endif
    {{ $attributes->merge(['class' => 'collapsible-section mt-6 first:mt-0 relative']) }}
    @if($open) open @endif
>
    @isset($actions)
        <div class="absolute top-0 right-0 z-10 flex items-center gap-2 h-[3.25rem] px-3 rounded-xl border border-gray-200 bg-white shadow-soft">
            {{ $actions }}
        </div>
    @endisset

    <summary @class([
        'flex items-center gap-2 cursor-pointer select-none rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-soft hover:border-blue-200 hover:shadow-card transition',
        'pr-28' => isset($actions),
    ])>
        <svg class="collapse-chevron w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <h2 class="section-title truncate">{{ $title }}</h2>
        @if($count !== null)
            <span class="badge-gray shrink-0">{{ $count }}</span>
        @endif
    </summary>

    @isset($subtitle)
        <div class="px-1 pt-2 text-sm text-gray-500 whitespace-pre-wrap">{{ $subtitle }}</div>
    @endisset

    <div class="pt-3 space-y-4">
        {{ $slot }}
    </div>
</details>

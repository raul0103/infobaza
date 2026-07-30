@props([
    'title',
    'subtitle' => null,
    'back' => null,
    'backLabel' => 'Назад',
])

<div {{ $attributes->merge(['class' => 'mb-6 sm:mb-8']) }}>
    @isset($breadcrumb)
        <nav class="mb-3" aria-label="Навигация">
            {{ $breadcrumb }}
        </nav>
    @elseif($back)
        <nav class="mb-3" aria-label="Навигация">
            <a href="{{ $back }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-blue-700 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ $backLabel }}
            </a>
        </nav>
    @endif

    <div class="flex flex-col gap-4">
        <div class="flex items-start justify-between gap-3 min-w-0">
            <div class="min-w-0 flex-1">
                <h1 class="page-title break-words">{{ $title }}</h1>
                @if($subtitle)<p class="page-subtitle">{{ $subtitle }}</p>@endif
            </div>
            @isset($titleActions)
                <div class="shrink-0 pt-0.5 sm:pt-1">{{ $titleActions }}</div>
            @endisset
        </div>
        @isset($actions)
            <div class="page-actions">{{ $actions }}</div>
        @endisset
    </div>
</div>

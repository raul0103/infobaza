@props(['href', 'title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'group/row relative flex items-stretch rounded-lg hover:bg-gray-50 transition-colors']) }}>
    <a href="{{ $href }}" class="absolute inset-0 z-0 rounded-lg" aria-label="{{ $title }}"></a>

    <div class="relative z-10 flex min-w-0 flex-1 items-center gap-3 pointer-events-none py-2.5 px-1">
        <h3 class="min-w-0 flex-1 text-sm font-medium text-gray-900 truncate group-hover/row:text-blue-600">{{ $title }}</h3>
        @if(filled($subtitle))
            <span class="text-xs text-gray-400 truncate max-w-[45%] shrink-0 hidden sm:inline">{{ $subtitle }}</span>
        @endif
    </div>

    @if(! $slot->isEmpty())
        <div class="relative z-10 flex shrink-0 items-center gap-1 py-1.5 pr-1">
            {{ $slot }}
        </div>
    @endif
</div>

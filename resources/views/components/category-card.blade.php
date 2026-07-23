@props(['href', 'title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'card-hover !p-3 flex items-center gap-2']) }}>
    <a href="{{ $href }}" class="min-w-0 flex-1">
        <div class="font-medium text-gray-900 hover:text-blue-600 truncate">{{ $title }}</div>
        @if(filled($subtitle))
            <div class="text-xs text-gray-500 mt-0.5">{{ $subtitle }}</div>
        @endif
    </a>
    {{ $slot }}
</div>

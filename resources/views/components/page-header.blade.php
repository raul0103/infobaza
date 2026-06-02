@props(['title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-4 mb-6 sm:mb-8']) }}>
    <div class="min-w-0">
        <h1 class="page-title break-words">{{ $title }}</h1>
        @if($subtitle)<p class="page-subtitle">{{ $subtitle }}</p>@endif
    </div>
    @isset($actions)
        <div class="page-actions">{{ $actions }}</div>
    @endisset
</div>

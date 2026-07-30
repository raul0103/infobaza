@props([
    'id',
    'show' => false,
    'subtitle' => null,
])

<div
    role="tabpanel"
    data-tab-panel="{{ $id }}"
    @class(['hidden' => ! $show])
    {{ $attributes }}
>
    @isset($actions)
        <div class="flex flex-wrap items-center justify-end gap-2 mb-3">
            {{ $actions }}
        </div>
    @endisset

    @if(filled($subtitle))
        <p class="text-sm text-gray-500 mb-3 whitespace-pre-wrap">{{ $subtitle }}</p>
    @endif

    {{ $slot }}
</div>

@props([
    'items' => [],
    'active' => null,
])

@php
    $active = $active ?? collect($items)->first()['id'] ?? null;
@endphp

<div
    {{ $attributes->class(['tabs']) }}
    data-tabs
    data-active="{{ $active }}"
>
    <div class="flex items-end gap-3 mb-4 border-b border-gray-200">
        <nav class="flex gap-0.5 overflow-x-auto scrollbar-none -mb-px flex-1 min-w-0" role="tablist" aria-label="Разделы">
            @foreach($items as $item)
                <button
                    type="button"
                    role="tab"
                    data-tab-btn="{{ $item['id'] }}"
                    aria-selected="{{ ($item['id'] === $active) ? 'true' : 'false' }}"
                    @class([
                        'shrink-0 inline-flex items-center gap-1.5 px-3 py-2.5 text-sm font-medium border-b-2 transition-colors',
                        'border-blue-600 text-blue-700' => $item['id'] === $active,
                        'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300' => $item['id'] !== $active,
                    ])
                >
                    <span class="truncate max-w-[10rem] sm:max-w-[14rem]">{{ $item['label'] }}</span>
                    @isset($item['count'])
                        <span class="text-xs tabular-nums text-gray-400">{{ $item['count'] }}</span>
                    @endisset
                </button>
            @endforeach
        </nav>
        @isset($actions)
            <div class="shrink-0 pb-2 hidden sm:block">{{ $actions }}</div>
        @endisset
    </div>

    <div class="tab-panels space-y-0">
        {{ $slot }}
    </div>
</div>

@once
@push('scripts')
<script>
function activateTab(root, panelId) {
    if (typeof root === 'string') {
        root = document.querySelector(root) || document.getElementById(root)?.closest('[data-tabs]');
    }
    if (!root || !panelId) return;

    root.dataset.active = panelId;

    root.querySelectorAll('[data-tab-btn]').forEach((btn) => {
        const on = btn.dataset.tabBtn === panelId;
        btn.setAttribute('aria-selected', on ? 'true' : 'false');
        btn.classList.toggle('border-blue-600', on);
        btn.classList.toggle('text-blue-700', on);
        btn.classList.toggle('border-transparent', !on);
        btn.classList.toggle('text-gray-500', !on);
    });

    root.querySelectorAll('[data-tab-panel]').forEach((panel) => {
        panel.classList.toggle('hidden', panel.dataset.tabPanel !== panelId);
    });

    if (history.replaceState) {
        history.replaceState(null, '', '#' + panelId);
    } else {
        location.hash = panelId;
    }
}

document.querySelectorAll('[data-tabs]').forEach((root) => {
    const fromHash = location.hash.replace(/^#/, '');
    const hashOk = fromHash && root.querySelector(`[data-tab-panel="${fromHash}"]`);
    const initial = hashOk ? fromHash : root.dataset.active;

    if (initial) activateTab(root, initial);

    root.querySelectorAll('[data-tab-btn]').forEach((btn) => {
        btn.addEventListener('click', () => activateTab(root, btn.dataset.tabBtn));
    });
});

window.addEventListener('hashchange', () => {
    const id = location.hash.replace(/^#/, '');
    if (!id) return;
    document.querySelectorAll('[data-tabs]').forEach((root) => {
        if (root.querySelector(`[data-tab-panel="${id}"]`)) {
            activateTab(root, id);
        }
    });
});
</script>
@endpush
@endonce

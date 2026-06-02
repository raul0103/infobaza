@props(['id', 'title', 'size' => 'md'])

@php
    $sizes = [
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-3xl',
    ];
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-[60] hidden p-0 sm:p-4" aria-modal="true" role="dialog" aria-labelledby="{{ $id }}-title">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" data-modal-backdrop onclick="closeModal('{{ $id }}')"></div>
    <div class="fixed inset-0 flex items-end sm:items-center justify-center pointer-events-none sm:p-4">
        <div class="pointer-events-auto bg-white rounded-t-2xl sm:rounded-2xl shadow-xl border border-gray-200 w-full {{ $sizes[$size] ?? $sizes['md'] }} max-h-[92dvh] sm:max-h-[85vh] flex flex-col">
            <div class="flex items-start justify-between gap-4 px-4 sm:px-6 py-4 border-b border-gray-100 shrink-0">
                <h2 id="{{ $id }}-title" class="text-lg font-semibold text-gray-900 pr-2 break-words">{{ $title }}</h2>
                <button type="button" onclick="closeModal('{{ $id }}')" class="rounded-lg p-2 -mr-1 text-gray-400 hover:bg-gray-100 shrink-0" aria-label="Закрыть">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="px-4 sm:px-6 py-4 sm:py-5 overflow-y-auto text-gray-700 leading-relaxed overscroll-contain">
                {{ $slot }}
            </div>
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-100 shrink-0 flex justify-stretch sm:justify-end safe-bottom">
                <button type="button" onclick="closeModal('{{ $id }}')" class="btn btn-secondary w-full sm:w-auto">Закрыть</button>
            </div>
        </div>
    </div>
</div>

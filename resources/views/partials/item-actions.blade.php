@props(['edit', 'destroy'])

<div class="shrink-0 relative" onclick="event.stopPropagation()">
    <details class="group relative action-menu">
        <summary class="list-none inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zm0 6a.75.75 0 110-1.5.75.75 0 010 1.5zm0 6a.75.75 0 110-1.5.75.75 0 010 1.5z"/>
            </svg>
        </summary>
        <div class="absolute right-0 z-20 mt-1 w-44 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-card">
            <a href="{{ $edit }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Редактировать</a>
            <form method="POST" action="{{ $destroy }}" onsubmit="return confirm('Удалить?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="block w-full px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50">Удалить</button>
            </form>
        </div>
    </details>
</div>

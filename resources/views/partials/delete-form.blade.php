@props(['action', 'compact' => false, 'label' => 'Удалить'])

<form method="POST" action="{{ $action }}" class="inline" onsubmit="return confirm('Удалить?')">
    @csrf
    @method('DELETE')
    @if($compact)
        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-700" title="{{ $label }}" aria-label="{{ $label }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 7.5h12m-10.5 0v10.125A2.625 2.625 0 0010.125 20.25h3.75A2.625 2.625 0 0016.5 17.625V7.5m-6 0V6a1.5 1.5 0 011.5-1.5h0A1.5 1.5 0 0113.5 6v1.5"/>
            </svg>
        </button>
    @else
        <button type="submit" class="btn btn-danger text-sm">{{ $label }}</button>
    @endif
</form>

@props(['action', 'compact' => false, 'label' => 'Удалить'])

<form method="POST" action="{{ $action }}" class="inline" onsubmit="return confirm('Удалить?')">
    @csrf
    @method('DELETE')
    @if($compact)
        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">{{ $label }}</button>
    @else
        <button type="submit" class="btn btn-danger text-sm">{{ $label }}</button>
    @endif
</form>

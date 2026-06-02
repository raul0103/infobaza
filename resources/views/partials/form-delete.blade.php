@props(['action', 'message' => 'Это действие необратимо.'])

<div class="mt-8 pt-6 border-t border-gray-200">
    <h3 class="text-sm font-semibold text-gray-900 mb-1">Удаление</h3>
    <p class="text-sm text-gray-500 mb-3">{{ $message }}</p>
    @include('partials.delete-form', ['action' => $action])
</div>

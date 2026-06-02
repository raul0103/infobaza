@props(['edit', 'destroy'])

<div class="flex flex-wrap items-center gap-x-3 gap-y-2 shrink-0" onclick="event.stopPropagation()">
    <a href="{{ $edit }}" class="link">Изменить</a>
    @include('partials.delete-form', ['action' => $destroy, 'compact' => true])
</div>

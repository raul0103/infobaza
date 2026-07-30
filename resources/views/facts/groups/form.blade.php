@extends('layouts.app')
@section('title', $group->exists ? 'Редактировать группу' : 'Новая группа фактов')

@section('content')
<x-form.shell
    :title="$group->exists ? 'Редактировать группу фактов' : 'Новая группа фактов'"
    :subtitle="$group->exists ? 'Объедините связанные факты' : 'Создайте группу — факты можно добавить позже'"
    :action="$group->exists ? route('fact-groups.update', $group) : route('fact-groups.store')"
    :method="$group->exists ? 'PUT' : 'POST'"
    :back="route('facts.index')"
    :wide="$group->exists"
>
    <x-form.input name="name" label="Название группы" :value="$group->name" required />
    <x-form.textarea
        name="description"
        label="Описание"
        :value="$group->description"
        :rows="3"
        hint="Необязательно. Коротко опишите, что объединяет факты."
    />

    @if($group->exists)
        <div class="form-group">
            <label class="label">Факты в группе</label>
            <p class="hint mb-3">Выберите нужные факты. Если факт уже находится в другой группе, он будет перенесён.</p>
            <div class="max-h-80 overflow-y-auto rounded-lg border border-gray-200 divide-y divide-gray-100">
                @forelse($facts as $fact)
                    @php
                        $selected = in_array($fact->id, $selectedFactIds, true)
                            || in_array((string) $fact->id, $selectedFactIds, true);
                        $inOtherGroup = $fact->fact_group_id
                            && ((int) $fact->fact_group_id !== (int) $group->id);
                    @endphp
                    <label class="flex items-start gap-3 px-3 py-3 hover:bg-gray-50 cursor-pointer">
                        <input
                            type="checkbox"
                            name="fact_ids[]"
                            value="{{ $fact->id }}"
                            class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            @checked($selected)
                        >
                        <span class="min-w-0">
                            <span class="block text-sm font-medium text-gray-900">
                                {{ $fact->title ?: Str::limit($fact->text, 80) }}
                            </span>
                            @if($fact->title)
                                <span class="block text-xs text-gray-500 mt-0.5">{{ Str::limit($fact->text, 110) }}</span>
                            @endif
                            @if($inOtherGroup)
                                <span class="badge-gray mt-1">Будет перенесён из другой группы</span>
                            @endif
                        </span>
                    </label>
                @empty
                    <p class="px-3 py-6 text-sm text-gray-500 text-center">Сначала добавьте факты.</p>
                @endforelse
            </div>
            @error('fact_ids.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    @endif
</x-form.shell>

@if($group->exists)
    <div class="max-w-4xl">
        @include('partials.form-delete', [
            'action' => route('fact-groups.destroy', $group),
            'message' => 'Группа будет удалена. Сами факты останутся и попадут в раздел «Без группы».',
        ])
    </div>
@endif
@endsection

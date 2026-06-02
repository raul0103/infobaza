@extends('layouts.app')
@section('title', 'Темы')
@section('content')
<x-page-header title="Темы" subtitle="Основные разделы с подтемами и отдельные категории">
    <x-slot:actions><a href="{{ route('topics.create') }}" class="btn btn-primary">+ Тема</a></x-slot:actions>
</x-page-header>

@if($groups['groups']->isEmpty() && $groups['standalone']->isEmpty())
    <div class="card text-center py-12">
        <p class="text-gray-500">Создайте темы: физика, электрика, видеонаблюдение…</p>
        <a href="{{ route('topics.create') }}" class="btn btn-primary mt-4">Создать тему</a>
    </div>
@else
    @if($groups['groups']->isNotEmpty())
        <section class="mb-10">
            <div class="flex items-center gap-3 mb-4">
                <h2 class="section-title">Основные разделы</h2>
                <span class="badge-gray">{{ $groups['groups']->count() }}</span>
            </div>
            <p class="text-sm text-gray-500 mb-5">Родительская тема и связанные с ней подтемы</p>
            <div class="space-y-5">
                @foreach($groups['groups'] as $parent)
                    @php $color = $parent->markColor(); @endphp
                    <div class="card p-0 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 bg-gray-50 border-b border-gray-100 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3 min-w-0">
                                @if($color)<span class="w-4 h-4 rounded-full shrink-0" style="background:{{ $color }}"></span>@endif
                                <div>
                                    <a href="{{ route('topics.show', $parent) }}" class="font-semibold text-lg text-gray-900 hover:text-blue-600">
                                        {{ $parent->name }}
                                    </a>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $parent->notes_count }} записей · {{ $parent->quotes_count }} цитат
                                        · {{ $parent->children->count() }} подтем
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                <a href="{{ route('topics.create', ['parent_id' => $parent->id]) }}" class="btn btn-ghost text-sm py-2 w-full sm:w-auto justify-center">+ Подтема</a>
                                @include('partials.item-actions', [
                                    'edit' => route('topics.edit', $parent),
                                    'destroy' => route('topics.destroy', $parent),
                                ])
                            </div>
                        </div>
                        <div class="p-4 grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($parent->children as $child)
                                @include('partials.topic-card', ['topic' => $child, 'nested' => true])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if($groups['standalone']->isNotEmpty())
        <section>
            <div class="flex items-center gap-3 mb-4">
                <h2 class="section-title">Отдельные темы</h2>
                <span class="badge-gray">{{ $groups['standalone']->count() }}</span>
            </div>
            <p class="text-sm text-gray-500 mb-5">Темы без подкатегорий</p>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($groups['standalone'] as $topic)
                    @include('partials.topic-card', ['topic' => $topic])
                @endforeach
            </div>
        </section>
    @endif
@endif
@endsection

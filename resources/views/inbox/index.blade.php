@extends('layouts.app')
@section('title', 'Инбокс')
@section('content')
<x-page-header title="Инбокс" subtitle="Быстрые мысли — разберите куда нужно">
    <x-slot:actions></x-slot:actions>
</x-page-header>

<form method="POST" action="{{ route('inbox.store') }}" class="card mb-6">@csrf
    <textarea name="content" class="textarea mb-3" rows="3" placeholder="Идея, название книги, слово, заметка…" required></textarea>
    <button class="btn btn-primary">Добавить</button>
</form>

<h2 class="section-title mb-3">Ожидают ({{ $pending->count() }})</h2>
@forelse($pending as $item)
    <div class="card mb-3">
        <p class="text-gray-800 whitespace-pre-wrap mb-4">{{ $item->content }}</p>
        @include('partials.inbox-convert', ['item' => $item, 'topicGroups' => $topicGroups, 'dictionaries' => $dictionaries])
        <form method="POST" action="{{ route('inbox.destroy', $item) }}" class="mt-2">@csrf @method('DELETE')
            <button class="text-sm text-red-600">Удалить</button>
        </form>
    </div>
@empty
    <p class="empty-state">Инбокс пуст</p>
@endforelse

@if($processed->isNotEmpty())
<h2 class="section-title mb-3 mt-8">Недавно разобрано</h2>
@foreach($processed as $item)
    <div class="card mb-2 py-3 text-sm flex justify-between items-center gap-4">
        <div class="min-w-0 flex-1">
            <p class="text-gray-600 line-clamp-1">{{ Str::limit($item->content, 80) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $item->processed_at->format('d.m.Y H:i') }}</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            @if($item->targetLabel())<span class="badge-gray">{{ $item->targetLabel() }}</span>@endif
            <form method="POST" action="{{ route('inbox.destroy', $item) }}">@csrf @method('DELETE')
                <button type="submit" class="text-sm text-red-600 hover:text-red-800">Удалить</button>
            </form>
        </div>
    </div>
@endforeach
@endif
@endsection

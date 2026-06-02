@extends('layouts.app')
@section('title', 'Пользователи')

@section('content')
    <x-page-header
        title="Пользователи"
        subtitle="Поиск профилей и просмотр открытых публикаций"
    />

    <form method="GET" class="card mb-4">
        <div class="grid sm:grid-cols-[1fr_auto] gap-3">
            <x-form.input name="q" label="Поиск по имени или логину" :value="$search" placeholder="например, ivan" />
            <div class="sm:pt-7">
                <button type="submit" class="btn-primary w-full sm:w-auto">Найти</button>
            </div>
        </div>
    </form>

    <div class="card">
        @forelse($users as $user)
            <article class="list-item">
                <div class="list-row">
                    <div>
                        <a href="{{ route('users.show', $user) }}" class="text-base font-semibold text-blue-700 hover:text-blue-900">
                            {{ $user->name }}
                        </a>
                        <p class="text-sm text-gray-500 mt-1">{{ '@'.$user->username }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="badge-gray">Книги: {{ $user->public_books_count }}</span>
                        <span class="badge-gray">Фильмы: {{ $user->public_movies_count }}</span>
                        <span class="badge-gray">Записи: {{ $user->public_notes_count }}</span>
                        <span class="badge-gray">Цитаты: {{ $user->public_quotes_count }}</span>
                        <span class="badge-gray">Словари: {{ $user->public_dictionaries_count }}</span>
                        <span class="badge-gray">Слова: {{ $user->public_words_count }}</span>
                    </div>
                </div>
            </article>
        @empty
            <p class="empty-state">Пользователи не найдены.</p>
        @endforelse

        <div class="pt-4">{{ $users->links() }}</div>
    </div>
@endsection

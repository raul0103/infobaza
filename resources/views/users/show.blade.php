@extends('layouts.app')
@section('title', $user->name)

@section('content')
    <x-page-header
        :title="$user->name"
        :subtitle="'@'.$user->username"
    />

    <section class="mb-6">
        <p class="text-sm text-gray-700">{{ $user->bio ?: 'Пользователь пока не добавил описание профиля.' }}</p>
        <p class="text-xs text-gray-500 mt-2">Показываются только открытые книги, цитаты, фильмы, записи, словари, слова и темы.</p>
    </section>

    @if(! $hasPublicContent)
        <section class="card">
            <p class="empty-state">Открытых публикаций нет. Доступна только краткая статистика профиля.</p>
        </section>
    @else
        <section class="space-y-8">
            <div>
                <h2 class="section-title mb-2">Книги</h2>
                @forelse($publicBooks as $book)
                    <article class="list-item">
                        <a href="{{ route('books.show', $book) }}" class="font-medium text-gray-900 hover:text-blue-700">{{ $book->title }}</a>
                        <p class="text-xs text-gray-500 mt-1">Цитат: {{ $book->quotes_count }}</p>
                    </article>
                @empty
                    <p class="empty-state">Нет открытых книг.</p>
                @endforelse
            </div>

            <div>
                <h2 class="section-title mb-2">Фильмы</h2>
                @forelse($publicMovies as $movie)
                    <article class="list-item">
                        <a href="{{ route('movies.show', $movie) }}" class="font-medium text-gray-900 hover:text-blue-700">{{ $movie->title }}</a>
                        <p class="text-xs text-gray-500 mt-1">Цитат: {{ $movie->quotes_count }}</p>
                    </article>
                @empty
                    <p class="empty-state">Нет открытых фильмов.</p>
                @endforelse
            </div>

            <div>
                <h2 class="section-title mb-2">Записи</h2>
                @forelse($publicNotes as $note)
                    <article class="list-item">
                        <a href="{{ route('notes.show', $note) }}" class="font-medium text-gray-900 hover:text-blue-700">{{ $note->title }}</a>
                        <p class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit(strip_tags($note->content), 90) }}</p>
                    </article>
                @empty
                    <p class="empty-state">Нет открытых записей.</p>
                @endforelse
            </div>

            <div>
                <h2 class="section-title mb-2">Цитаты</h2>
                @forelse($publicQuotes as $quote)
                    <article class="list-item">
                        <p class="text-sm text-gray-900 line-clamp-3">{{ $quote->text }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $quote->sourceLabel() }}</p>
                    </article>
                @empty
                    <p class="empty-state">Нет открытых цитат.</p>
                @endforelse
            </div>

            <div>
                <h2 class="section-title mb-2">Словари</h2>
                @forelse($publicDictionaries as $dictionary)
                    <article class="list-item">
                        <a href="{{ route('dictionaries.show', $dictionary) }}" class="font-medium text-gray-900 hover:text-blue-700">{{ $dictionary->name }}</a>
                        <p class="text-xs text-gray-500 mt-1">Слов: {{ $dictionary->entries_count }}</p>
                    </article>
                @empty
                    <p class="empty-state">Нет открытых словарей.</p>
                @endforelse
            </div>

            <div>
                <h2 class="section-title mb-2">Слова</h2>
                @forelse($publicWords as $word)
                    <article class="list-item">
                        <p class="font-medium text-gray-900">{{ $word->term }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($word->definition, 90) }}</p>
                    </article>
                @empty
                    <p class="empty-state">Нет открытых слов.</p>
                @endforelse
            </div>

            <div>
                <h2 class="section-title mb-2">Темы</h2>
                @forelse($publicTopics as $topic)
                    <article class="list-item">
                        <a href="{{ route('topics.show', $topic) }}" class="font-medium text-gray-900 hover:text-blue-700">{{ $topic->name }}</a>
                        <p class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($topic->description, 90) }}</p>
                    </article>
                @empty
                    <p class="empty-state">Нет открытых тем.</p>
                @endforelse
            </div>
        </section>
    @endif
@endsection

@extends('layouts.app')
@section('title', 'Повторение — все слова')

@section('content')
<div class="max-w-lg mx-auto w-full px-1 sm:px-0">
    <a href="{{ route('dictionaries.index') }}" class="link mb-6 inline-flex items-center gap-1">← Назад к словарям</a>

    <div class="text-center mb-8">
        <span class="badge-blue">Все словари</span>
        <p class="text-sm text-gray-500 mt-2">{{ $total }} слов</p>
    </div>

    @if($entry)
        <div class="card-form text-center py-10" id="card">
            <div id="term-view">
                <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Слово</p>
                @if($entry->dictionary)
                    <p class="text-xs text-gray-400 mb-4">{{ $entry->dictionary->name }}</p>
                @endif
                <div class="text-3xl font-bold text-gray-900 mb-8">{{ $entry->term }}</div>
                <button type="button" onclick="showAnswer()" class="btn btn-primary">Показать ответ</button>
            </div>
            <div id="answer-view" class="hidden">
                <p class="text-xs uppercase tracking-wide text-gray-400 mb-4">Значение</p>
                <div class="text-xl text-gray-800 mb-4 leading-relaxed">{{ $entry->definition }}</div>
                @if($entry->example)<p class="text-gray-500 italic mb-8 text-sm">«{{ $entry->example }}»</p>@endif
                <form method="POST" action="{{ route('review.all.answer', $entry) }}">
                    @csrf
                    <button class="btn btn-primary">Дальше</button>
                </form>
            </div>
        </div>
    @else
        <div class="card text-center py-12">
            <p class="text-gray-500">Пока нет слов для повторения.</p>
            <a href="{{ route('dictionaries.index') }}" class="btn btn-primary mt-4">К словарям</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function showAnswer() {
    document.getElementById('term-view').classList.add('hidden');
    document.getElementById('answer-view').classList.remove('hidden');
}
</script>
@endpush

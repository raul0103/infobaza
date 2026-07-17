@extends('layouts.app')
@section('title', 'Повторение — '.$dictionary->name)

@section('content')
<div class="max-w-lg mx-auto w-full px-1 sm:px-0">
    <a href="{{ route('review.index') }}" class="link mb-6 inline-flex items-center gap-1">← Назад к словарям</a>

    <div class="text-center mb-8">
        <span class="badge-blue">{{ $dictionary->name }}</span>
        <p class="text-sm text-gray-500 mt-2">{{ $total }} слов</p>
    </div>

    @if($entry)
        <div class="card-form text-center py-10" id="card">
            <div id="term-view">
                <p class="text-xs uppercase tracking-wide text-gray-400 mb-4">Слово</p>
                <div class="text-3xl font-bold text-gray-900 mb-8">{{ $entry->term }}</div>
                <button type="button" onclick="showAnswer()" class="btn btn-primary">Показать ответ</button>
            </div>
            <div id="answer-view" class="hidden">
                <p class="text-xs uppercase tracking-wide text-gray-400 mb-4">Значение</p>
                <div class="text-xl text-gray-800 mb-4 leading-relaxed">{{ $entry->definition }}</div>
                @if($entry->example)<p class="text-gray-500 italic mb-8 text-sm">«{{ $entry->example }}»</p>@endif
                <form method="POST" action="{{ route('review.answer', [$dictionary, $entry]) }}">
                    @csrf
                    <button class="btn btn-primary">Дальше</button>
                </form>
            </div>
        </div>
    @else
        <div class="card text-center py-12">
            <p class="text-gray-500">В словаре нет слов.</p>
            <a href="{{ route('dictionaries.entries.create', $dictionary) }}" class="btn btn-primary mt-4">Добавить слово</a>
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

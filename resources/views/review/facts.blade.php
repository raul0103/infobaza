@extends('layouts.app')
@section('title', 'Повторение — Интересные факты')

@section('content')
<div class="max-w-lg mx-auto w-full px-1 sm:px-0">
    <a href="{{ route('review.index') }}" class="link mb-6 inline-flex items-center gap-1">← Назад к повторению</a>

    <div class="text-center mb-8">
        <span class="badge-blue">Интересные факты</span>
        <p class="text-sm text-gray-500 mt-2">{{ $total }} фактов</p>
    </div>

    @if($fact)
        <div class="card-form text-center py-10" id="card">
            @if($fact->title)
                <div id="term-view">
                    <p class="text-xs uppercase tracking-wide text-gray-400 mb-4">Вспомните факт</p>
                    <div class="text-2xl font-bold text-gray-900 mb-8">{{ $fact->title }}</div>
                    <button type="button" onclick="showAnswer()" class="btn btn-primary">Показать факт</button>
                </div>
            @endif
            <div id="answer-view" class="{{ $fact->title ? 'hidden' : '' }}">
                <p class="text-xs uppercase tracking-wide text-gray-400 mb-4">Факт</p>
                <div class="text-lg text-gray-800 mb-4 leading-relaxed whitespace-pre-wrap text-left sm:text-center">{{ $fact->text }}</div>
                @if($fact->source)<p class="text-gray-500 italic mb-8 text-sm">Источник: {{ $fact->source }}</p>@endif
                <form method="POST" action="{{ route('review.facts.answer', $fact) }}">
                    @csrf
                    <button class="btn btn-primary">Дальше</button>
                </form>
            </div>
        </div>
    @else
        <div class="card text-center py-12">
            <p class="text-gray-500">Фактов пока нет.</p>
            <a href="{{ route('facts.create') }}" class="btn btn-primary mt-4">Добавить факт</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function showAnswer() {
    document.getElementById('term-view')?.classList.add('hidden');
    document.getElementById('answer-view').classList.remove('hidden');
}
</script>
@endpush

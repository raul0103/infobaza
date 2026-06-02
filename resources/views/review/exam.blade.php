@extends('layouts.app')
@section('title', 'Экзамен')
@section('content')
<div class="max-w-lg mx-auto w-full">
    <x-page-header title="Экзамен" :subtitle="'Вопросов к повторению: '.$dueCount" />
    @if($question)
        <div class="card-form text-center py-8" id="q-view">
            <p class="text-xs uppercase text-gray-400 mb-4">Вопрос</p>
            <p class="text-lg font-medium text-gray-900 mb-6">{{ $question->question }}</p>
            <p class="text-sm text-gray-500 mb-4">Из записи: {{ $question->note->title }}</p>
            <button type="button" onclick="document.getElementById('q-view').classList.add('hidden');document.getElementById('a-view').classList.remove('hidden')" class="btn btn-primary">Показать ответ</button>
        </div>
        <div class="card-form hidden" id="a-view">
            <p class="text-gray-800 mb-6 whitespace-pre-wrap">{{ $question->answer }}</p>
            <div class="flex gap-3 justify-center flex-wrap">
                <form method="POST" action="{{ route('review.exam.answer', $question) }}">@csrf<input type="hidden" name="known" value="0"><button class="btn btn-danger">Не помню</button></form>
                <form method="POST" action="{{ route('review.exam.answer', $question) }}">@csrf<input type="hidden" name="known" value="1"><button class="btn btn-success">Помню</button></form>
            </div>
        </div>
    @else
        <div class="card text-center py-12">
            <p class="text-gray-500 mb-4">Добавьте вопросы к записям</p>
            <a href="{{ route('notes.index') }}" class="btn btn-primary">К записям</a>
        </div>
    @endif
</div>
@endsection

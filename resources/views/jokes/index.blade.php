@extends('layouts.app')
@section('title', 'Анекдоты')

@section('content')
<x-page-header title="Анекдоты" subtitle="Избранные анекдоты">
    <x-slot:actions>
        <a href="{{ route('jokes.create') }}" class="btn btn-primary">+ Анекдот</a>
    </x-slot:actions>
</x-page-header>

<div class="space-y-2">
    @forelse($jokes as $joke)
        @include('jokes.card', ['joke' => $joke])
    @empty
        <div class="card text-center py-12 text-gray-500">Пока нет сохранённых анекдотов</div>
    @endforelse
</div>

<div class="mt-6">{{ $jokes->links() }}</div>
@endsection

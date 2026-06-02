@extends('layouts.app')
@section('title', $entry->entry_date->format('d.m.Y'))
@section('content')
<x-page-header :title="$entry->entry_date->translatedFormat('d F Y')" :subtitle="$entry->title">
    <x-slot:actions>
        @if($entry->mood)<span class="badge-gray">{{ $entry->mood }}</span>@endif
        <a href="{{ route('journal.edit', $entry) }}" class="btn btn-secondary">Изменить</a>
        @include('partials.delete-form', ['action' => route('journal.destroy', $entry)])
    </x-slot:actions>
</x-page-header>
<div class="card">
    <div class="whitespace-pre-wrap text-gray-700 leading-relaxed text-base">{{ $entry->content }}</div>
</div>
@endsection

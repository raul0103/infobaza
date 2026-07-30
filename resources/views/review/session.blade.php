@extends('layouts.app')
@section('title', 'Повторение — '.$dictionary->name)

@section('content')
<div class="max-w-3xl mx-auto w-full px-1 sm:px-0" data-csrf="{{ csrf_token() }}">
    <div class="flex items-center justify-between gap-3 mb-6">
        <a href="{{ route('dictionaries.show', $dictionary) }}" class="link inline-flex items-center gap-1">← Назад к «{{ $dictionary->name }}»</a>
        @if($entries->isNotEmpty())
            <a href="{{ route('review.session', $dictionary) }}" class="btn btn-secondary text-sm shrink-0">Обновить</a>
        @endif
    </div>

    <div class="text-center mb-6">
        <span class="badge-blue">{{ $dictionary->name }}</span>
        <p class="text-sm text-gray-500 mt-2">{{ $total }} слов · пачка из {{ $entries->count() }}</p>
    </div>

    @if($entries->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="review-batch">
            @foreach($entries as $entry)
                <button
                    type="button"
                    class="card-hover !p-4 sm:!p-5 text-center min-h-[7.5rem] flex flex-col items-center justify-center cursor-pointer"
                    data-term="{{ rawurlencode($entry->term) }}"
                    data-definition="{{ rawurlencode($entry->definition) }}"
                    data-definition-html="{{ rawurlencode(\App\Support\Markdown::parse($entry->definition)->toHtml()) }}"
                    data-example="{{ rawurlencode((string) $entry->example) }}"
                    data-example-html="{{ rawurlencode(\App\Support\Markdown::parse($entry->example)->toHtml()) }}"
                    data-answer-url="{{ route('review.answer', [$dictionary, $entry]) }}"
                    onclick="openReviewCard(this)"
                >
                    <span class="text-lg sm:text-xl font-bold text-gray-900 leading-snug break-words">{{ $entry->term }}</span>
                </button>
            @endforeach
        </div>
        <p class="text-center text-xs text-gray-400 mt-6">Откройте карточку — слово отметится как повторённое</p>
    @else
        <div class="card text-center py-12">
            <p class="text-gray-500">В словаре нет слов.</p>
            <a href="{{ route('dictionaries.entries.create', $dictionary) }}" class="btn btn-primary mt-4">Добавить слово</a>
        </div>
    @endif
</div>

@include('partials.entry-detail-modal')
@endsection

@push('scripts')
@include('review.partials.batch-script')
@endpush

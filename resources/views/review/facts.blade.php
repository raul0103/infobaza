@extends('layouts.app')
@section('title', 'Повторение — '.$badge)

@section('content')
<div class="max-w-3xl mx-auto w-full px-1 sm:px-0" data-csrf="{{ csrf_token() }}">
    <div class="flex items-center justify-between gap-3 mb-6">
        <a href="{{ $backUrl }}" class="link inline-flex items-center gap-1">← К выбору</a>
        @if($facts->isNotEmpty())
            <a href="{{ $refreshUrl }}" class="btn btn-secondary text-sm shrink-0">Обновить</a>
        @endif
    </div>

    <div class="text-center mb-6">
        <span class="badge-blue">{{ $badge }}</span>
        <p class="text-sm text-gray-500 mt-2">{{ $total }} фактов · пачка из {{ $facts->count() }}</p>
    </div>

    @if($facts->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="review-batch">
            @foreach($facts as $fact)
                @php
                    $preview = $fact->title ?: Str::limit($fact->text, 60);
                @endphp
                <button
                    type="button"
                    class="card-hover !p-4 sm:!p-5 text-center min-h-[7.5rem] flex flex-col items-center justify-center gap-2 cursor-pointer"
                    data-kind="fact"
                    data-title="{{ rawurlencode((string) $fact->title) }}"
                    data-text="{{ rawurlencode($fact->text) }}"
                    data-text-html="{{ rawurlencode(\App\Support\Markdown::parse($fact->text)->toHtml()) }}"
                    data-source-label="{{ rawurlencode((string) $fact->source) }}"
                    data-answer-url="{{ route($answerRoute, $fact) }}"
                    onclick="openReviewCard(this)"
                >
                    @if($showGroupBadge && $fact->group)
                        <span class="badge-blue text-[11px] max-w-full truncate">{{ $fact->group->name }}</span>
                    @endif
                    <span class="text-base sm:text-lg font-bold text-gray-900 leading-snug break-words">{{ $preview }}</span>
                </button>
            @endforeach
        </div>
        <p class="text-center text-xs text-gray-400 mt-6">Откройте карточку — факт отметится как повторённый</p>
    @else
        <div class="card text-center py-12">
            <p class="text-gray-500">Фактов пока нет.</p>
            <a href="{{ $emptyUrl }}" class="btn btn-primary mt-4">{{ $emptyLabel }}</a>
        </div>
    @endif
</div>

@include('partials.card-detail-modal')
@endsection

@push('scripts')
@include('review.partials.batch-script')
@endpush

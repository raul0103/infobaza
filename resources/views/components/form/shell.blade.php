@props(['title', 'subtitle' => null, 'action', 'method' => 'POST', 'back' => null, 'backLabel' => 'Отмена', 'wide' => false])

<x-page-header :title="$title" :subtitle="$subtitle" />

<div class="w-full {{ $wide ? 'max-w-4xl' : 'max-w-2xl' }}">
    <div class="card-form">
        <form method="POST" action="{{ $action }}" class="space-y-5">
            @csrf
            @if(strtoupper($method) !== 'POST')
                @method($method)
            @endif

            {{ $slot }}

            <div class="flex flex-col-reverse sm:flex-row flex-wrap items-stretch sm:items-center gap-3 pt-6 mt-2 border-t border-gray-100">
                @if($back)
                    <a href="{{ $back }}" class="btn btn-secondary w-full sm:w-auto">{{ $backLabel }}</a>
                @endif
                <button type="submit" class="btn btn-primary w-full sm:w-auto {{ $back ? 'sm:ml-auto' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Сохранить
                </button>
            </div>
        </form>
    </div>
</div>

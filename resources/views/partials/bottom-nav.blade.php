@php
    $tabs = [
        [
            'href' => route('dashboard'),
            'label' => 'Главная',
            'icon' => 'home',
            'active' => request()->routeIs('dashboard'),
        ],
        [
            'href' => route('dictionaries.index'),
            'label' => 'Словари',
            'icon' => 'dictionary',
            'active' => request()->routeIs('dictionaries.*', 'review.index', 'review.session', 'review.answer', 'review.all', 'review.all.answer'),
        ],
        [
            'href' => route('books.index'),
            'label' => 'Книги',
            'icon' => 'book',
            'active' => request()->routeIs('books.*', 'quotes.*', 'tips.*', 'phrases.*', 'review.phrases', 'review.phrases.*'),
        ],
        [
            'href' => route('memos.index'),
            'label' => 'Заметки',
            'icon' => 'memo',
            'active' => request()->routeIs('memos.*'),
        ],
    ];
@endphp

<nav class="fixed bottom-0 inset-x-0 z-30 border-t border-gray-200 bg-white/95 backdrop-blur lg:hidden safe-bottom" aria-label="Быстрая навигация">
    <div class="grid grid-cols-5 h-[3.75rem]">
        @foreach($tabs as $tab)
            <a
                href="{{ $tab['href'] }}"
                @class([
                    'flex flex-col items-center justify-center gap-0.5 text-[10px] font-medium transition-colors',
                    'text-blue-700' => $tab['active'],
                    'text-gray-500 hover:text-gray-800' => ! $tab['active'],
                ])
            >
                @include('partials.nav-icon', [
                    'name' => $tab['icon'],
                    'class' => 'nav-icon !w-[1.35rem] !h-[1.35rem] '.($tab['active'] ? '!text-blue-600' : '!text-gray-400'),
                ])
                <span>{{ $tab['label'] }}</span>
            </a>
        @endforeach
        <button
            type="button"
            id="bottom-nav-more"
            class="flex flex-col items-center justify-center gap-0.5 text-[10px] font-medium text-gray-500 hover:text-gray-800 transition-colors"
            aria-label="Открыть меню"
        >
            <svg class="w-[1.35rem] h-[1.35rem] text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
            <span>Ещё</span>
        </button>
    </div>
</nav>

@php
    $sections = [
        [
            'label' => 'Обзор',
            'items' => [
                ['route' => 'dashboard', 'label' => 'Главная', 'icon' => 'home'],
                ['route' => 'today.*', 'label' => 'Сегодня', 'icon' => 'today'],
            ],
        ],
        [
            'label' => 'База знаний',
            'items' => [
                ['route' => 'topics.*', 'label' => 'Темы', 'icon' => 'folder'],
                ['route' => 'notes.*', 'label' => 'Записи', 'icon' => 'document'],
            ],
        ],
        [
            'label' => 'Медиатека',
            'items' => [
                ['route' => 'books.*', 'label' => 'Книги', 'icon' => 'book'],
                ['route' => 'movies.*', 'label' => 'Фильмы', 'icon' => 'film'],
                ['route' => 'quotes.*', 'label' => 'Цитаты', 'icon' => 'quote'],
            ],
        ],
        [
            'label' => 'Обучение',
            'items' => [
                ['route' => 'dictionaries.*', 'label' => 'Словари', 'icon' => 'dictionary'],
                ['route' => 'review.*', 'label' => 'Повторение', 'icon' => 'repeat'],
            ],
        ],
        [
            'label' => 'Планирование',
            'items' => [
                ['route' => 'inbox.*', 'label' => 'Инбокс', 'icon' => 'inbox'],
                ['route' => 'reminders.*', 'label' => 'Напоминания', 'icon' => 'bell'],
                ['route' => 'events.*', 'label' => 'События', 'icon' => 'calendar'],
                ['route' => 'journal.*', 'label' => 'Дневник', 'icon' => 'journal'],
            ],
        ],
        [
            'label' => 'Справка',
            'items' => [
                ['route' => 'guide.*', 'label' => 'Руководство', 'icon' => 'help'],
            ],
        ],
    ];
@endphp

@foreach($sections as $section)
    <p class="nav-section-label">{{ $section['label'] }}</p>
    <div class="space-y-0.5 mb-1">
        @foreach($section['items'] as $item)
            @php
                $href = $item['route'] === 'dashboard'
                    ? route('dashboard')
                    : route(str_replace('.*', '.index', $item['route']));
                $active = $item['route'] === 'dashboard'
                    ? request()->routeIs('dashboard')
                    : request()->routeIs($item['route']);
            @endphp
            <a href="{{ $href }}" class="nav-link {{ $active ? 'active' : '' }}">
                @include('partials.nav-icon', ['name' => $item['icon']])
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
    @if(! $loop->last)
        <div class="my-3 border-t border-gray-100" role="separator"></div>
    @endif
@endforeach

@extends('layouts.app')
@section('title', 'Руководство')

@section('content')
<x-page-header title="Руководство" subtitle="Как пользоваться MyLive — без спешки, в своём темпе" />

<div class="lg:grid lg:grid-cols-[220px_1fr] lg:gap-8 items-start">
    <nav class="card mb-6 lg:mb-0 lg:sticky lg:top-6 text-sm">
        <p class="font-semibold text-gray-900 mb-3">Содержание</p>
        <ul class="space-y-2 text-gray-600">
            <li><a href="#about" class="link block">Что это</a></li>
            <li><a href="#flow" class="link block">С чего начать</a></li>
            <li><a href="#inbox" class="link block">Инбокс</a></li>
            <li><a href="#topics" class="link block">Темы и записи</a></li>
            <li><a href="#books" class="link block">Книги</a></li>
            <li><a href="#movies" class="link block">Фильмы</a></li>
            <li><a href="#quotes" class="link block">Цитаты</a></li>
            <li><a href="#dictionaries" class="link block">Словари</a></li>
            <li><a href="#review" class="link block">Повторение</a></li>
            <li><a href="#today" class="link block">Сегодня</a></li>
            <li><a href="#planning" class="link block">Планирование</a></li>
            <li><a href="#mobile" class="link block">iPhone и iPad</a></li>
            <li><a href="#tips" class="link block">Советы</a></li>
        </ul>
    </nav>

    <div class="space-y-6 guide-prose">
        <section id="about" class="card scroll-mt-24">
            <h2 class="section-title mb-3">Что такое MyLive</h2>
            <p class="text-gray-600 leading-relaxed">
                MyLive — личная база знаний на вашем компьютере. Сюда складывают мысли, конспекты, книги, слова и цитаты,
                а потом спокойно разбирают и повторяют. Никто не торопит: нет целей, дедлайнов и «ты должен сегодня…».
            </p>
            <p class="text-gray-600 leading-relaxed mt-3">
                Данные хранятся в файле <code class="text-sm bg-gray-100 px-1.5 py-0.5 rounded">database/database.sqlite</code> —
                это ваша локальная база, можно делать резервные копии и переносить между машинами.
            </p>
        </section>

        <section id="flow" class="card scroll-mt-24">
            <h2 class="section-title mb-3">С чего начать</h2>
            <ol class="list-decimal list-inside space-y-2 text-gray-600 leading-relaxed">
                <li>Запишите мысль в <a href="{{ route('inbox.index') }}" class="link">Инбокс</a> — не думая, куда она относится.</li>
                <li>Когда будет время — откройте инбокс и выберите, куда сохранить: запись, книга, фильм или слово.</li>
                <li>Создайте <a href="{{ route('topics.index') }}" class="link">темы</a> (Физика, Работа…) и привязывайте к ним записи.</li>
                <li>Для запоминания — <a href="{{ route('dictionaries.index') }}" class="link">словари</a> и <a href="{{ route('review.index') }}" class="link">повторение</a>.</li>
            </ol>
        </section>

        <section id="inbox" class="card scroll-mt-24">
            <h2 class="section-title mb-3">Инбокс</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                Быстрый захват: название книги, термин, идея для статьи — всё в одно поле. Позже разберёте.
            </p>
            <p class="text-sm font-medium text-gray-800 mb-2">Куда можно сохранить:</p>
            <ul class="space-y-2 text-gray-600 text-sm">
                <li><strong>Запись</strong> — полноценная заметка с темой и текстом из инбокса.</li>
                <li><strong>Книга → на очереди</strong> — попадёт в раздел «На очереди» в книгах; текст — в описание.</li>
                <li><strong>Фильм → на очереди</strong> — то же для фильмов.</li>
                <li><strong>Слово в словарь</strong> — первая строка = слово, остальное = значение (можно поправить перед сохранением).</li>
            </ul>
            <p class="text-gray-500 text-sm mt-4">
                В блоке «Недавно разобрано» можно удалить запись из истории инбокса — созданная книга или запись не удалится.
            </p>
        </section>

        <section id="topics" class="card scroll-mt-24">
            <h2 class="section-title mb-3">Темы и записи</h2>
            <p class="text-gray-600 leading-relaxed mb-3">
                <strong>Темы</strong> — папки для знаний. Можно делать вложенные (родитель → подтема). У родительской темы есть цвет для бейджей.
            </p>
            <p class="text-gray-600 leading-relaxed mb-3">
                <strong>Записи</strong> — основной контент: конспекты, справки, идеи.
            </p>
            <ul class="space-y-2 text-gray-600 text-sm list-disc list-inside">
                <li><strong>Уровень усвоения</strong> — от «Не разобрал» до «Могу применить» (для себя, без оценок).</li>
                <li><strong>Пересказ</strong> — своими словами, чтобы лучше запомнить.</li>
                <li><strong>Связанные записи</strong> — ссылки между заметками (Ctrl+клик для нескольких в форме).</li>
                <li><strong>Вопросы для повторения</strong> — на странице записи; используются в режиме «Экзамен».</li>
            </ul>
        </section>

        <section id="books" class="card scroll-mt-24">
            <h2 class="section-title mb-3">Книги</h2>
            <p class="text-gray-600 leading-relaxed mb-3">Статусы:</p>
            <ul class="space-y-1 text-gray-600 text-sm mb-4">
                @foreach(\App\Models\Book::statusLabels() as $label)
                    <li>• {{ $label }}</li>
                @endforeach
            </ul>
            <p class="text-gray-600 leading-relaxed">
                Укажите <strong>всего страниц</strong> и <strong>текущую страницу</strong> — появится полоска прогресса.
                Или в редактировании: поле <strong>«Прочитал сегодня»</strong> — число прибавится к текущей странице.
                После прочтения можно записать <strong>главный вывод</strong> и добавлять <strong>цитаты</strong> со страницами.
            </p>
        </section>

        <section id="movies" class="card scroll-mt-24">
            <h2 class="section-title mb-3">Фильмы</h2>
            <p class="text-gray-600 leading-relaxed">
                Как у книг: разделы <strong>На очереди</strong>, <strong>Смотрю</strong>, <strong>Просмотрено</strong>.
                Из инбокса фильм сразу попадает «на очереди». Цитаты и реплики персонажей — на странице фильма.
            </p>
        </section>

        <section id="quotes" class="card scroll-mt-24">
            <h2 class="section-title mb-3">Цитаты</h2>
            <p class="text-gray-600 leading-relaxed">
                Общий список или добавление с страницы книги/фильма. Можно указать страницу, персонажа, тему и контекст.
            </p>
        </section>

        <section id="dictionaries" class="card scroll-mt-24">
            <h2 class="section-title mb-3">Словари</h2>
            <p class="text-gray-600 leading-relaxed">
                Создайте словарь (язык, тема), добавляйте слова: термин, определение, пример.
                Слова из инбокса тоже можно сразу отправить в выбранный словарь.
            </p>
        </section>

        <section id="review" class="card scroll-mt-24">
            <h2 class="section-title mb-3">Повторение</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                <strong>Карточки</strong> — интервальное повторение (SRS): «Помню» увеличивает интервал, «Не помню» — показывает раньше.
                На главной странице повторения видно, сколько карточек готово к повтору.
            </p>
            <p class="text-gray-600 leading-relaxed">
                <strong>Экзамен</strong> — вопросы, которые вы сами добавили к записям. Откройте запись → блок «Вопросы для повторения» → добавьте вопрос и ответ.
            </p>
        </section>

        <section id="today" class="card scroll-mt-24">
            <h2 class="section-title mb-3">Сегодня</h2>
            <p class="text-gray-600 leading-relaxed">
                Страница дня: быстрый инбокс, счётчик «к повторению», активность за сегодня (записи, карточки, страницы…),
                случайный «урок дня» (цитата, запись или слово). Удобно открыть утром или когда сели учиться — без лишних цифр на главной.
            </p>
        </section>

        <section id="planning" class="card scroll-mt-24">
            <h2 class="section-title mb-3">Планирование</h2>
            <ul class="space-y-3 text-gray-600 leading-relaxed">
                <li><strong>Напоминания</strong> — дата и время, можно отметить выполненным.</li>
                <li><strong>События</strong> — встречи, дедлайны, целый день или с временем.</li>
                <li><strong>Дневник</strong> — запись за день: текст, настроение, заголовок.</li>
            </ul>
            <p class="text-gray-600 leading-relaxed mt-4">
                На <a href="{{ route('dashboard') }}" class="link">главной</a> — напоминания, события, последние записи, что читаете сейчас, дневник за сегодня.
            </p>
        </section>

        <section id="mobile" class="card scroll-mt-24">
            <h2 class="section-title mb-3">iPhone и iPad</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                MyLive — сайт, а не приложение из App Store. На телефоне он открывается в Safari и может стоять на экране «Домой» как обычная иконка.
                <strong>Компьютер и телефон должны быть в одной Wi‑Fi сети</strong> (или телефон — через VPN к домашней сети).
            </p>

            <h3 class="text-sm font-semibold text-gray-800 mb-2">1. Запуск на ПК</h3>
            <p class="text-gray-600 text-sm mb-2">Вместо обычного <code class="bg-gray-100 px-1 rounded">php artisan serve</code> выполните в папке проекта:</p>
            <pre class="text-sm bg-gray-900 text-gray-100 rounded-lg p-4 overflow-x-auto mb-4">php artisan serve --host=0.0.0.0 --port=8000</pre>
            <p class="text-gray-600 text-sm mb-4">
                Через OSPanel: укажите домен на папку <code class="bg-gray-100 px-1 rounded">public</code> и откройте этот адрес с телефона, если OSPanel доступен в локальной сети.
            </p>

            <h3 class="text-sm font-semibold text-gray-800 mb-2">2. Узнайте адрес ПК</h3>
            <p class="text-gray-600 text-sm mb-2">В PowerShell на Windows:</p>
            <pre class="text-sm bg-gray-900 text-gray-100 rounded-lg p-4 overflow-x-auto mb-2">ipconfig</pre>
            <p class="text-gray-600 text-sm mb-4">Нужен <strong>IPv4</strong> Wi‑Fi адаптера, например <code class="bg-gray-100 px-1 rounded">192.168.1.42</code>.</p>

            <h3 class="text-sm font-semibold text-gray-800 mb-2">3. Откройте на iPhone</h3>
            <p class="text-gray-600 text-sm mb-4">
                Safari → адресная строка → <code class="bg-gray-100 px-1 rounded">http://192.168.1.42:8000</code> (подставьте свой IP).
                Меню ☰ вверху слева — навигация по разделам.
            </p>

            <h3 class="text-sm font-semibold text-gray-800 mb-2">4. Иконка на экране «Домой»</h3>
            <ol class="list-decimal list-inside space-y-1 text-gray-600 text-sm mb-4">
                <li>В Safari нажмите «Поделиться» (квадрат со стрелкой).</li>
                <li>«На экран Домой» → «Добавить».</li>
            </ol>
            <p class="text-gray-600 text-sm mb-4">
                Откроется почти как приложение, без панели Safari. Данные по-прежнему на вашем ПК — без интернета вне домашней сети сайт не откроется.
            </p>

            <h3 class="text-sm font-semibold text-gray-800 mb-2">Если не открывается</h3>
            <ul class="space-y-1 text-gray-600 text-sm list-disc list-inside">
                <li>Разрешите PHP или порт 8000 в брандмауэре Windows.</li>
                <li>Проверьте, что iPhone не в гостевой Wi‑Fi, изолированной от ПК.</li>
                <li>На ПК в браузере сначала откройте <code class="bg-gray-100 px-1 rounded">http://ВАШ_IP:8000</code> — должно работать.</li>
            </ul>

            <p class="text-gray-500 text-sm mt-4">
                В App Store отдельного MyLive нет: для доступа из любой точки мира нужен сервер в интернете (VPS, хостинг) с установленным Laravel — это отдельная настройка.
            </p>
        </section>

        <section id="tips" class="card scroll-mt-24">
            <h2 class="section-title mb-3">Советы</h2>
            <ul class="space-y-2 text-gray-600 text-sm list-disc list-inside">
                <li>Не обязаны разбирать инбокс каждый день — пусть копится, разберёте когда удобно.</li>
                <li>Одна мысль — один инбокс; не нужно сразу выбирать правильную категорию.</li>
                <li>Для языка: слово в инбокс → в словарь → повторение карточками.</li>
                <li>Для конспекта: запись + вопросы → экзамен через пару дней.</li>
                <li>Резервная копия: скопируйте файл <code class="bg-gray-100 px-1 rounded">database/database.sqlite</code>.</li>
            </ul>
        </section>
    </div>
</div>
@endsection

@push('head')
<style>
    .guide-prose section p + p { margin-top: 0.75rem; }
    .guide-prose code { font-family: ui-monospace, monospace; }
</style>
@endpush

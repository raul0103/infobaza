# infobaza — личная база знаний

Локальный сервис на **Laravel + SQLite** для одного человека: заметки по темам, цитаты, словари с интервальным повторением.

База данных — файл `database/database.sqlite`, его можно **хранить в Git** вместе с проектом.

## Возможности

| Раздел | Описание |
|--------|----------|
| **Главная** | Обзор: слова, цитаты, что читаете |
| **Словари** | Слова и определения, повторение (SRS) |
| **Книги / Фильмы** | Прогресс чтения, цитаты |
| **Темы / Записи** | Конспекты, связи, вопросы для экзамена |
| **Заметки** | Личные мысли и советы по категориям |
| **Интересные факты** | Короткие любопытные сведения |
| **Анекдоты** | Избранные анекдоты |
| **Повторение** | Карточки по расписанию и режим «Экзамен» |

## Установка (OSPanel / локально)

```bash
cd c:\OSPanel\home\_infobaza
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed   # опционально: примеры тем и слов
```

Откройте: http://infobaza.local

Встроенное руководство: http://infobaza.local/guide (также в меню → **Руководство**).

### OSPanel

В `.osp/project.ini` уже указан домен `infobaza.local` и `public_dir` → `public/`. После создания/изменения конфига перезапустите OSPanel (или модули Nginx/Apache + PHP).

## Git и SQLite

1. Файл БД: `database/database.sqlite`
2. После изменений данных: `git add database/database.sqlite` и коммит
3. На другой машине: `git pull` → данные уже в файле
4. **Не коммитьте** `.env` (секреты и локальные настройки)

При первом клоне, если файла БД нет:

```bash
type nul > database\database.sqlite
php artisan migrate
php artisan db:seed
```

## Команды

```bash
php artisan migrate          # новые таблицы
php artisan db:seed          # демо-данные
php artisan migrate:fresh --seed   # сброс БД (осторожно!)
```

## Стек

- PHP 8.2+
- Laravel 13
- SQLite
- Blade + Tailwind (CDN)

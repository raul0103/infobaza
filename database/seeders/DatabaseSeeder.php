<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Dictionary;
use App\Models\DictionaryEntry;
use App\Models\Note;
use App\Models\Quote;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            ['name' => 'Физика', 'color' => '#3b82f6'],
            ['name' => 'Электрика', 'color' => '#f59e0b'],
            ['name' => 'Видеонаблюдение', 'color' => '#10b981'],
        ];

        foreach ($topics as $data) {
            Topic::create($data);
        }

        $physics = Topic::where('name', 'Физика')->first();

        Note::create([
            'topic_id' => $physics->id,
            'title' => 'Закон Ома',
            'content' => "U = I × R\n\nНапряжение равно силе тока, умноженной на сопротивление.",
        ]);

        $book = Book::create([
            'title' => 'Пример книги',
            'author' => 'Автор',
            'status' => 'reading',
            'current_page' => 42,
            'total_pages' => 200,
        ]);

        Quote::create([
            'book_id' => $book->id,
            'text' => 'Учиться никогда не поздно.',
            'page' => '42',
        ]);

        $dict = Dictionary::create([
            'name' => 'Английский — базовый',
            'language' => 'en',
            'description' => 'Пример словаря для повторения',
        ]);

        foreach (
            [
                ['term' => 'resilience', 'definition' => 'устойчивость, способность восстанавливаться'],
                ['term' => 'deliberate', 'definition' => 'намеренный, обдуманный'],
            ] as $word
        ) {
            DictionaryEntry::create([...$word, 'dictionary_id' => $dict->id]);
        }
    }
}

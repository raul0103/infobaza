<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'books',
            'notes',
            'quotes',
            'movies',
            'dictionaries',
            'dictionary_entries',
            'topics',
            'events',
            'reminders',
        ] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'visibility')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropColumn('visibility');
                });
            }
        }

        if (Schema::hasColumn('books', 'review_takeaway')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('review_takeaway');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'books',
            'notes',
            'quotes',
            'movies',
            'dictionaries',
            'dictionary_entries',
            'topics',
            'events',
            'reminders',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'visibility')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->string('visibility')->default('private');
                });
            }
        }

        if (! Schema::hasColumn('books', 'review_takeaway')) {
            Schema::table('books', function (Blueprint $table) {
                $table->text('review_takeaway')->nullable();
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('topics')) {
            Schema::table('topics', function (Blueprint $table) {
                try {
                    $table->dropUnique('topics_user_id_slug_unique');
                } catch (\Throwable) {
                    //
                }

                try {
                    $table->unique('slug', 'topics_slug_unique');
                } catch (\Throwable) {
                    //
                }
            });
        }

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
            'inbox_items',
        ] as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'user_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                try {
                    $blueprint->dropConstrainedForeignId('user_id');
                } catch (\Throwable) {
                    try {
                        $blueprint->dropForeign(['user_id']);
                    } catch (\Throwable) {
                        //
                    }
                    $blueprint->dropColumn('user_id');
                }
            });
        }
    }

    public function down(): void
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
            'inbox_items',
        ] as $table) {
            if (! Schema::hasTable($table) || Schema::hasColumn($table, 'user_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            });
        }

        if (Schema::hasTable('topics')) {
            Schema::table('topics', function (Blueprint $table) {
                try {
                    $table->dropUnique('topics_slug_unique');
                } catch (\Throwable) {
                    //
                }
                $table->unique(['user_id', 'slug'], 'topics_user_id_slug_unique');
            });
        }
    }
};

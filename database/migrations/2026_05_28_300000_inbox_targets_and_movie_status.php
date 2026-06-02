<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->string('status')->default('queued')->after('description');
        });

        Schema::table('inbox_items', function (Blueprint $table) {
            $table->foreignId('book_id')->nullable()->after('note_id')->constrained()->nullOnDelete();
            $table->foreignId('movie_id')->nullable()->after('book_id')->constrained()->nullOnDelete();
            $table->foreignId('dictionary_entry_id')->nullable()->after('movie_id')->constrained()->nullOnDelete();
        });

        DB::table('books')->where('status', 'planned')->update(['status' => 'queued']);
    }

    public function down(): void
    {
        Schema::table('inbox_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dictionary_entry_id');
            $table->dropConstrainedForeignId('movie_id');
            $table->dropConstrainedForeignId('book_id');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        DB::table('books')->where('status', 'queued')->update(['status' => 'planned']);
    }
};

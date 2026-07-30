<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('book_thoughts', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
        });

        Schema::table('book_thoughts', function (Blueprint $table) {
            $table->unsignedBigInteger('book_id')->nullable()->change();
            $table->foreign('book_id')->references('id')->on('books')->cascadeOnDelete();
            $table->foreignId('movie_id')->nullable()->after('book_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('book_thoughts', function (Blueprint $table) {
            $table->dropForeign(['movie_id']);
            $table->dropColumn('movie_id');
            $table->dropForeign(['book_id']);
        });

        Schema::table('book_thoughts', function (Blueprint $table) {
            $table->unsignedBigInteger('book_id')->nullable(false)->change();
            $table->foreign('book_id')->references('id')->on('books')->cascadeOnDelete();
        });
    }
};

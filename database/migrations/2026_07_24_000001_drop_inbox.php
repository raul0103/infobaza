<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('inbox_items');

        if (Schema::hasColumn('daily_activities', 'inbox_processed')) {
            Schema::table('daily_activities', function (Blueprint $table) {
                $table->dropColumn('inbox_processed');
            });
        }
    }

    public function down(): void
    {
        Schema::create('inbox_items', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('note_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('book_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('movie_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('dictionary_entry_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('daily_activities', function (Blueprint $table) {
            $table->unsignedSmallInteger('inbox_processed')->default(0);
        });
    }
};

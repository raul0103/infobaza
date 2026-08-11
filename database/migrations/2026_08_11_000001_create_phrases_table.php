<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phrases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('movie_id')->nullable()->constrained()->cascadeOnDelete();
            $table->text('text');
            $table->text('note')->nullable();
            $table->string('page', 50)->nullable();
            $table->string('character')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->timestamp('next_review_at')->nullable();
            $table->unsignedSmallInteger('interval_days')->default(1);
            $table->unsignedInteger('review_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phrases');
    }
};

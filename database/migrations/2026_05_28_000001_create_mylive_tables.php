<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 7)->default('#6366f1');
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('topics')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('content');
            $table->string('type')->default('note'); // note, reference, idea
            $table->json('tags')->nullable();
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('reading'); // reading, finished, planned
            $table->timestamps();
        });

        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('director')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('movie_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->text('text');
            $table->string('page')->nullable();
            $table->string('character')->nullable();
            $table->text('context')->nullable();
            $table->timestamps();
        });

        Schema::create('dictionaries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('language')->nullable();
            $table->timestamps();
        });

        Schema::create('dictionary_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dictionary_id')->constrained()->cascadeOnDelete();
            $table->string('term');
            $table->text('definition');
            $table->text('example')->nullable();
            $table->unsignedTinyInteger('mastery_level')->default(0);
            $table->timestamp('last_reviewed_at')->nullable();
            $table->unsignedInteger('review_count')->default(0);
            $table->timestamps();
        });

        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body')->nullable();
            $table->dateTime('remind_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->boolean('all_day')->default(false);
            $table->string('location')->nullable();
            $table->timestamps();
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->string('title')->nullable();
            $table->text('content');
            $table->string('mood')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('events');
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('dictionary_entries');
        Schema::dropIfExists('dictionaries');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('movies');
        Schema::dropIfExists('books');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('topics');
    }
};

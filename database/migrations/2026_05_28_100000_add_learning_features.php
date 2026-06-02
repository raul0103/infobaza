<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedInteger('current_page')->nullable()->after('year');
            $table->unsignedInteger('total_pages')->nullable()->after('current_page');
            $table->date('started_at')->nullable()->after('total_pages');
            $table->date('finished_at')->nullable()->after('started_at');
            $table->text('review_takeaway')->nullable()->after('description');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->unsignedTinyInteger('mastery_level')->default(0)->after('content');
            $table->text('recap')->nullable()->after('mastery_level');
        });

        Schema::table('dictionary_entries', function (Blueprint $table) {
            $table->timestamp('next_review_at')->nullable()->after('example');
            $table->unsignedSmallInteger('interval_days')->default(1)->after('next_review_at');
            $table->unsignedInteger('review_count')->default(0)->after('interval_days');
        });

        Schema::create('daily_activities', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->unsignedSmallInteger('notes_count')->default(0);
            $table->unsignedSmallInteger('cards_reviewed')->default(0);
            $table->unsignedSmallInteger('journal_count')->default(0);
            $table->unsignedSmallInteger('quotes_count')->default(0);
            $table->unsignedSmallInteger('pages_read')->default(0);
            $table->unsignedSmallInteger('inbox_processed')->default(0);
            $table->timestamps();
        });

        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('period')->default('week');
            $table->string('metric');
            $table->unsignedInteger('target_value');
            $table->unsignedInteger('current_value')->default(0);
            $table->date('starts_at');
            $table->date('ends_at');
            $table->unsignedSmallInteger('challenge_days')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('inbox_items', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignId('note_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('note_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained()->cascadeOnDelete();
            $table->string('question');
            $table->text('answer');
            $table->timestamp('next_review_at')->nullable();
            $table->unsignedSmallInteger('interval_days')->default(1);
            $table->unsignedInteger('review_count')->default(0);
            $table->timestamps();
        });

        Schema::create('note_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained()->cascadeOnDelete();
            $table->foreignId('linked_note_id')->constrained('notes')->cascadeOnDelete();
            $table->unique(['note_id', 'linked_note_id']);
        });

        Schema::create('weekly_reviews', function (Blueprint $table) {
            $table->id();
            $table->date('week_start')->unique();
            $table->text('content');
            $table->text('key_learning')->nullable();
            $table->timestamps();
        });

        Schema::create('wins', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->date('achieved_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wins');
        Schema::dropIfExists('weekly_reviews');
        Schema::dropIfExists('note_links');
        Schema::dropIfExists('note_questions');
        Schema::dropIfExists('inbox_items');
        Schema::dropIfExists('goals');
        Schema::dropIfExists('daily_activities');

        Schema::table('dictionary_entries', function (Blueprint $table) {
            $table->dropColumn(['next_review_at', 'interval_days', 'review_count']);
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['mastery_level', 'recap']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['current_page', 'total_pages', 'started_at', 'finished_at', 'review_takeaway']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('journal_entries');

        if (Schema::hasColumn('daily_activities', 'journal_count')) {
            Schema::table('daily_activities', function (Blueprint $table) {
                $table->dropColumn('journal_count');
            });
        }
    }

    public function down(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('entry_date');
            $table->string('title')->nullable();
            $table->text('content');
            $table->string('mood')->nullable();
            $table->string('visibility')->default('private');
            $table->timestamps();
            $table->index(['user_id', 'visibility']);
        });

        Schema::table('daily_activities', function (Blueprint $table) {
            $table->unsignedSmallInteger('journal_count')->default(0)->after('cards_reviewed');
        });
    }
};

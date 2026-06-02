<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('visibility')->default('private')->after('status');
            $table->index(['user_id', 'visibility']);
        });

        Schema::table('dictionaries', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('visibility')->default('private')->after('language');
            $table->index(['user_id', 'visibility']);
        });

        Schema::table('dictionary_entries', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('visibility')->default('private')->after('example');
            $table->index(['user_id', 'visibility']);
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('visibility')->default('private')->after('description');
            $table->index(['user_id', 'visibility']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('visibility')->default('private')->after('location');
            $table->index(['user_id', 'visibility']);
        });

        Schema::table('reminders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('visibility')->default('private')->after('completed_at');
            $table->index(['user_id', 'visibility']);
        });

        Schema::table('journal_entries', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('visibility')->default('private')->after('mood');
            $table->index(['user_id', 'visibility']);
        });
    }

    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'visibility']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('visibility');
        });

        Schema::table('reminders', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'visibility']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('visibility');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'visibility']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('visibility');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'visibility']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('visibility');
        });

        Schema::table('dictionary_entries', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'visibility']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('visibility');
        });

        Schema::table('dictionaries', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'visibility']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('visibility');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'visibility']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('visibility');
        });
    }
};

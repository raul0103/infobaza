<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->text('bio')->nullable()->after('password');
        });

        $userIds = DB::table('users')->pluck('id');
        foreach ($userIds as $userId) {
            DB::table('users')
                ->where('id', $userId)
                ->update(['username' => 'user'.$userId]);
        }

        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('visibility')->default('private')->after('status');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('visibility')->default('private')->after('title');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('visibility')->default('private')->after('topic_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('username');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->index(['user_id', 'visibility']);
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->index(['user_id', 'visibility']);
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->index(['user_id', 'visibility']);
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'visibility']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('visibility');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'visibility']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('visibility');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'visibility']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('visibility');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['username']);
            $table->dropColumn(['username', 'bio']);
        });
    }
};

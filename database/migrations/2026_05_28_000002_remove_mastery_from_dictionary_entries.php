<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dictionary_entries', function (Blueprint $table) {
            $table->dropColumn(['mastery_level', 'last_reviewed_at', 'review_count']);
        });
    }

    public function down(): void
    {
        Schema::table('dictionary_entries', function (Blueprint $table) {
            $table->unsignedTinyInteger('mastery_level')->default(0);
            $table->timestamp('last_reviewed_at')->nullable();
            $table->unsignedInteger('review_count')->default(0);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropUnique('topics_slug_unique');
            $table->unique(['user_id', 'slug'], 'topics_user_id_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropUnique('topics_user_id_slug_unique');
            $table->unique('slug', 'topics_slug_unique');
        });
    }
};

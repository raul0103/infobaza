<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facts', function (Blueprint $table) {
            $table->timestamp('next_review_at')->nullable()->after('source');
            $table->unsignedSmallInteger('interval_days')->default(1)->after('next_review_at');
            $table->unsignedInteger('review_count')->default(0)->after('interval_days');
        });
    }

    public function down(): void
    {
        Schema::table('facts', function (Blueprint $table) {
            $table->dropColumn(['next_review_at', 'interval_days', 'review_count']);
        });
    }
};

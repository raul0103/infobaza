<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('wins');
        Schema::dropIfExists('weekly_reviews');
        Schema::dropIfExists('goals');
    }

    public function down(): void
    {
        // Tables are recreated by 2026_05_28_100000_add_learning_features if needed.
    }
};

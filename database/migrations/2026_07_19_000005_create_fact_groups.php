<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fact_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('facts', function (Blueprint $table) {
            $table->foreignId('fact_group_id')
                ->nullable()
                ->after('id')
                ->constrained('fact_groups')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('facts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('fact_group_id');
        });

        Schema::dropIfExists('fact_groups');
    }
};

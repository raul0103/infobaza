<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dictionary_entry_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('dictionary_entries')->cascadeOnDelete();
            $table->foreignId('related_entry_id')->constrained('dictionary_entries')->cascadeOnDelete();
            $table->string('type', 16);
            $table->unique(['entry_id', 'related_entry_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dictionary_entry_relations');
    }
};

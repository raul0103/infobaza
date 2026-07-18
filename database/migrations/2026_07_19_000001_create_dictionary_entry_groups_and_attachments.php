<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dictionary_entry_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dictionary_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('dictionary_entries', function (Blueprint $table) {
            $table->foreignId('group_id')
                ->nullable()
                ->after('dictionary_id')
                ->constrained('dictionary_entry_groups')
                ->nullOnDelete();
        });

        Schema::create('dictionary_group_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dictionary_entry_group_id')
                ->constrained('dictionary_entry_groups')
                ->cascadeOnDelete();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dictionary_group_attachments');

        Schema::table('dictionary_entries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('group_id');
        });

        Schema::dropIfExists('dictionary_entry_groups');
    }
};

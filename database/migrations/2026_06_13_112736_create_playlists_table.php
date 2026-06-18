<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('loop_mode', ['sequential', 'shuffle'])->default('sequential');
            $table->unsignedInteger('default_image_duration')->default(10);
            $table->enum('transition_effect', ['none', 'fade', 'slide', 'crossfade'])->default('none');
            $table->timestamps();

            $table->index('organization_id', 'idx_playlists_org');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};

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
        Schema::create('playlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('playlist_id')->constrained()->cascadeOnDelete();
            $table->enum('content_type', ['media', 'widget'])->default('media');
            $table->unsignedBigInteger('media_asset_id')->nullable();
            $table->unsignedBigInteger('widget_id')->nullable(); // For future roadmap
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('custom_duration')->nullable();
            $table->timestamps();

            $table->foreign('media_asset_id')->references('id')->on('media_assets')->cascadeOnDelete();
            // constraint to ensure exactly one is set will be omitted here since widgets don't exist yet, but logic is easy
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlist_items');
    }
};

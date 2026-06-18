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
        Schema::create('screens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('device_id')->unique();
            $table->string('registration_code', 10)->nullable()->unique();
            $table->enum('status', ['unregistered', 'online', 'offline', 'degraded', 'decommissioned'])->default('unregistered');
            $table->string('player_version', 50)->nullable();
            $table->string('resolution', 20)->nullable();
            $table->enum('orientation', ['landscape', 'portrait'])->nullable();
            $table->unsignedBigInteger('default_media_id')->nullable();
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->timestamps();

            $table->index('location_id', 'idx_screens_location');
            $table->index('status', 'idx_screens_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screens');
    }
};

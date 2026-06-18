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
        Schema::table('screens', function (Blueprint $table) {
            $table->integer('volume')->default(80)->after('default_media_id');
            $table->boolean('is_playing')->default(true)->after('volume');
            $table->foreignId('current_media_id')->nullable()->after('is_playing')->constrained('media_assets')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('screens', function (Blueprint $table) {
            $table->dropForeign(['current_media_id']);
            $table->dropColumn(['volume', 'is_playing', 'current_media_id']);
        });
    }
};

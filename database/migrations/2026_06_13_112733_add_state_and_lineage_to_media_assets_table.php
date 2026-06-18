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
        Schema::table('media_assets', function (Blueprint $table) {
            $table->enum('status', ['uploading', 'processing', 'ready', 'failed', 'deleted'])
                  ->default('ready') // Default to ready for existing records
                  ->after('duration');
            $table->enum('source', ['upload', 'designer', 'platform'])
                  ->default('upload') // Default to upload for existing records
                  ->after('status');
            $table->unsignedBigInteger('derived_from_id')->nullable()->after('source');
            
            $table->foreign('derived_from_id')->references('id')->on('media_assets')->nullOnDelete();

            $table->index('status', 'idx_media_status');
            $table->index('source', 'idx_media_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_assets', function (Blueprint $table) {
            $table->dropForeign(['derived_from_id']);
            $table->dropIndex('idx_media_status');
            $table->dropIndex('idx_media_source');
            $table->dropColumn(['status', 'source', 'derived_from_id']);
        });
    }
};

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
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['max_screens', 'max_storage_bytes']);
            
            $table->integer('price_inr_monthly')->default(0)->after('stripe_price_id');
            $table->integer('price_inr_yearly')->default(0)->after('price_inr_monthly');
            $table->integer('screens_limit')->default(0)->after('price_inr_yearly');
            $table->integer('locations_limit')->default(0)->after('screens_limit');
            $table->integer('managers_limit')->default(0)->after('locations_limit');
            $table->integer('storage_gb')->default(0)->after('managers_limit');
            $table->integer('campaigns_limit')->default(-1)->after('storage_gb');
            $table->integer('playlists_limit')->default(-1)->after('campaigns_limit');
            $table->integer('media_limit')->default(-1)->after('playlists_limit');
            
            $table->boolean('is_active')->default(true)->after('features');
            $table->integer('sort_order')->default(0)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'price_inr_monthly', 'price_inr_yearly', 'screens_limit',
                'locations_limit', 'managers_limit', 'storage_gb',
                'campaigns_limit', 'playlists_limit', 'media_limit',
                'is_active', 'sort_order'
            ]);
            
            $table->integer('max_screens')->default(0);
            $table->bigInteger('max_storage_bytes')->default(0);
        });
    }
};

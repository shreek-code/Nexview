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
            $table->dropColumn([
                'price_inr_monthly', 'price_inr_yearly', 'screens_limit',
                'locations_limit', 'managers_limit', 'storage_gb',
                'campaigns_limit', 'playlists_limit', 'media_limit', 'features'
            ]);

            $table->string('payment_model')->nullable()->after('stripe_price_id');
            $table->string('billing_cycle')->nullable()->after('payment_model');
            $table->boolean('remote_access')->default(true)->after('billing_cycle');
            $table->string('network_restriction')->nullable()->after('remote_access');
            
            $table->json('limits')->nullable()->after('network_restriction');
            $table->json('analytics')->nullable()->after('limits');
            $table->json('widgets')->nullable()->after('analytics');
            $table->json('broadcasts')->nullable()->after('widgets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('price_inr_monthly')->default(0);
            $table->integer('price_inr_yearly')->default(0);
            $table->integer('screens_limit')->default(0);
            $table->integer('locations_limit')->default(0);
            $table->integer('managers_limit')->default(0);
            $table->integer('storage_gb')->default(0);
            $table->integer('campaigns_limit')->default(-1);
            $table->integer('playlists_limit')->default(-1);
            $table->integer('media_limit')->default(-1);
            $table->json('features')->nullable();

            $table->dropColumn([
                'payment_model', 'billing_cycle', 'remote_access', 'network_restriction',
                'limits', 'analytics', 'widgets', 'broadcasts'
            ]);
        });
    }
};

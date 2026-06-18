<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create campaign_screen table
        Schema::create('campaign_screen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('screen_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['campaign_id', 'screen_id'], 'uq_campaign_screen');
        });

        // 2. Add columns to campaigns table
        Schema::table('campaigns', function (Blueprint $table) {
            $table->unsignedInteger('priority')->default(0)->after('status');
            $table->enum('content_type', ['playlist', 'media', 'widget'])->default('playlist')->after('priority');
            $table->unsignedBigInteger('playlist_id')->nullable()->after('content_type');
            $table->unsignedBigInteger('media_asset_id')->nullable()->after('playlist_id');
            $table->unsignedBigInteger('widget_id')->nullable()->after('media_asset_id');
            $table->enum('target_type', ['screens', 'location'])->default('location')->after('widget_id');
            $table->unsignedBigInteger('target_location_id')->nullable()->after('target_type');
            
            $table->date('date_start')->nullable()->after('target_location_id');
            $table->date('date_end')->nullable()->after('date_start');
            $table->time('time_start')->nullable()->after('date_end');
            $table->time('time_end')->nullable()->after('time_start');
            $table->string('recurrence')->nullable()->after('time_end');

            $table->foreign('playlist_id')->references('id')->on('playlists')->nullOnDelete();
            $table->foreign('media_asset_id')->references('id')->on('media_assets')->nullOnDelete();
            $table->foreign('target_location_id')->references('id')->on('locations')->nullOnDelete();
        });

        // 3. Migrate Data
        $campaigns = DB::table('campaigns')->get();
        foreach ($campaigns as $campaign) {
            $dateStart = $campaign->start_date ? date('Y-m-d', strtotime($campaign->start_date)) : null;
            $dateEnd = $campaign->end_date ? date('Y-m-d', strtotime($campaign->end_date)) : null;

            // Handle Target Migration
            $locations = DB::table('campaign_locations')->where('campaign_id', $campaign->id)->get();
            $targetLocationId = null;
            $targetType = 'location';

            if ($locations->count() > 0) {
                $targetLocationId = $locations->first()->location_id;
                
                foreach ($locations as $loc) {
                    $screens = DB::table('screens')->where('location_id', $loc->location_id)->get();
                    foreach ($screens as $screen) {
                        DB::table('campaign_screen')->insertOrIgnore([
                            'campaign_id' => $campaign->id,
                            'screen_id' => $screen->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            } else {
                $targetType = 'screens';
            }

            // Handle Content Migration
            $mediaItems = DB::table('campaign_media')->where('campaign_id', $campaign->id)->orderBy('order')->get();
            $playlistId = null;

            if ($mediaItems->count() > 0) {
                $playlistId = DB::table('playlists')->insertGetId([
                    'organization_id' => $campaign->organization_id,
                    'name' => $campaign->name . ' Playlist',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($mediaItems as $item) {
                    DB::table('playlist_items')->insert([
                        'playlist_id' => $playlistId,
                        'content_type' => 'media',
                        'media_asset_id' => $item->media_asset_id,
                        'sort_order' => $item->order,
                        'custom_duration' => $item->duration,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::table('campaigns')->where('id', $campaign->id)->update([
                'priority' => 0,
                'content_type' => 'playlist',
                'playlist_id' => $playlistId,
                'target_type' => $targetType,
                'target_location_id' => $targetLocationId,
                'date_start' => $dateStart,
                'date_end' => $dateEnd,
            ]);
        }

        // 4. Drop old columns and tables
        Schema::dropIfExists('campaign_media');
        Schema::dropIfExists('campaign_locations');

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
        });
        
        Schema::dropIfExists('campaign_screen');
        
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropForeign(['playlist_id']);
            $table->dropForeign(['media_asset_id']);
            $table->dropForeign(['target_location_id']);
            $table->dropColumn([
                'priority', 'content_type', 'playlist_id', 'media_asset_id', 
                'widget_id', 'target_type', 'target_location_id', 
                'date_start', 'date_end', 'time_start', 'time_end', 'recurrence'
            ]);
        });
    }
};

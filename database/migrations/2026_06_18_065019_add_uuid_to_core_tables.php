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
        $tables = ['users', 'locations', 'screens', 'playlists', 'campaigns', 'media_assets'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id')->unique();
            });
        }

        // Populate UUIDs for existing records
        $models = [
            \App\Models\User::class,
            \App\Models\Location::class,
            \App\Models\Screen::class,
            \App\Models\Playlist::class,
            \App\Models\Campaign::class,
            \App\Models\MediaAsset::class,
        ];

        foreach ($models as $modelClass) {
            foreach ($modelClass::withoutGlobalScopes()->get() as $model) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
                $model->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['users', 'locations', 'screens', 'playlists', 'campaigns', 'media_assets'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('uuid');
            });
        }
    }
};

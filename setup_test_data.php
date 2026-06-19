<?php
use App\Models\Organization;
use App\Models\Location;
use App\Models\MediaAsset;
use App\Models\Campaign;

$org = Organization::first();
if (!$org) {
    $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org']);
}

$location = Location::firstOrCreate(['organization_id' => $org->id], ['name' => 'Test Location', 'address' => '123 Test St']);

$media = MediaAsset::firstOrCreate(
    ['organization_id' => $org->id, 'type' => 'image'], 
    [
        'name' => 'Test Media', 
        'file_path' => 'media/test.jpg', 
        'disk' => 'public', 
        'mime_type' => 'image/jpeg', 
        'size' => 1000,
        'hash' => 'dummyhash'
    ]
);

$campaign = Campaign::firstOrCreate(
    ['organization_id' => $org->id, 'name' => 'Test Campaign'], 
    [
        'priority' => 10, 
        'status' => 'active', 
        'target_type' => 'location', 
        'target_location_id' => $location->id, 
        'content_type' => 'media', 
        'media_asset_id' => $media->id
    ]
);

echo json_encode([
    'org_id' => $org->id,
    'location_id' => $location->id,
    'media_id' => $media->id,
    'campaign_id' => $campaign->id
]);

<?php

namespace App\Services;

use App\Models\Location;
use App\Models\Organization;

class LocationService
{
    public function createLocation(Organization $organization, array $data): Location
    {
        return Location::create([
            'organization_id' => $organization->id,
            'name' => $data['name'],
            'timezone' => $data['timezone'],
            'address' => $data['address'] ?? '',
        ]);
    }
}

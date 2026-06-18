<?php

namespace Database\Seeders;

use App\Models\PlatformUser;
use Illuminate\Database\Seeder;

class PlatformUserSeeder extends Seeder
{
    public function run(): void
    {
        PlatformUser::create([
            'name' => 'Platform Admin',
            'email' => 'admin@nexview.io',
            'password' => bcrypt('password'),
        ]);
    }
}

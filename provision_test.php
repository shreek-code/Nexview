<?php
use App\Models\User;
use App\Models\Screen;
use App\Services\ScreenService;

$user = User::first();
if (!$user) {
    echo "No user found\n";
    exit;
}

$code = $argv[1] ?? 'TST123';

try {
    app(ScreenService::class)->provisionScreen($user, [
        'registration_code' => $code,
        'name' => 'Test Screen',
        'location_id' => 1
    ]);
    echo "Provisioned successfully\n";
} catch (\Exception $e) {
    echo "Error provisioning: " . $e->getMessage() . "\n";
}

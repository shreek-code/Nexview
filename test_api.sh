#!/bin/bash
set -e

BASE_URL="http://127.0.0.1:8000/api"
DEVICE_ID="test-dev-$(date +%s)"
CODE=$(head /dev/urandom | tr -dc A-Z0-9 | head -c 6)

echo "=== 1. Registration (Pending) ==="
RES=$(curl -s -X POST "$BASE_URL/player/register" \
    -H "Content-Type: application/json" \
    -d "{\"registration_code\": \"$CODE\", \"device_id\": \"$DEVICE_ID\", \"player_version\": \"1.0\"}")
echo $RES
echo ""

echo "=== Provisioning Screen via Artisan ==="
php artisan tinker --execute="app(App\Services\ScreenService::class)->provisionScreen(App\Models\User::first(), ['registration_code' => '$CODE', 'name' => 'Test Screen', 'location_id' => 1]);"
echo ""

echo "=== 2. Registration (Success & Token) ==="
RES=$(curl -s -X POST "$BASE_URL/player/register" \
    -H "Content-Type: application/json" \
    -d "{\"registration_code\": \"$CODE\", \"device_id\": \"$DEVICE_ID\", \"player_version\": \"1.0\"}")
echo $RES
TOKEN=$(echo $RES | grep -o '"token":"[^"]*' | grep -o '[^"]*$')
echo "Token: $TOKEN"
echo ""

if [ -z "$TOKEN" ]; then
    echo "Failed to get token"
    exit 1
fi

echo "=== 3. Heartbeat ==="
RES=$(curl -s -X POST "$BASE_URL/player/heartbeat" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Accept: application/json")
echo $RES
echo ""

echo "=== 4. Sync ==="
RES=$(curl -s -X GET "$BASE_URL/player/sync" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Accept: application/json")
echo $RES
echo ""

echo "=== 5. Media Download ==="
MEDIA_ID=$(echo $RES | grep -o '"id":[0-9]*' | head -1 | grep -o '[0-9]*')
if [ ! -z "$MEDIA_ID" ]; then
    RES=$(curl -s -X GET "$BASE_URL/player/media/$MEDIA_ID" \
        -H "Authorization: Bearer $TOKEN" \
        -H "Accept: application/json")
    echo $RES
else
    echo "No media ID found in sync"
fi
echo ""

echo "=== 6. Analytics ==="
RES=$(curl -s -X POST "$BASE_URL/player/analytics" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -d "{\"logs\":[{\"media_asset_id\":1,\"campaign_id\":1,\"duration_seconds\":10,\"played_at\":\"$(date -u +%Y-%m-%dT%H:%M:%SZ)\"}]}")
echo $RES
echo ""

echo "=== 7. WebSocket Push (Test Auth) ==="
RES=$(curl -s -X POST "$BASE_URL/broadcasting/auth" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -d "{\"channel_name\": \"private-organization.1\", \"socket_id\": \"1234.5678\"}")
echo $RES
echo ""

echo "=== 8. Offline Recovery (Check Command) ==="
php artisan screens:check-heartbeats
echo "Command executed"

<?php

namespace App\Events;

use App\Models\Campaign;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $campaignId;
    public $organizationId;

    public function __construct(Campaign $campaign)
    {
        $this->campaignId = $campaign->id;
        $this->organizationId = $campaign->organization_id;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('organization.' . $this->organizationId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'campaign.deleted';
    }
}

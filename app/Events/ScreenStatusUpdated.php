<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Screen;

class ScreenStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $screen;

    /**
     * Create a new event instance.
     */
    public function __construct(Screen $screen)
    {
        $this->screen = $screen;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('organization.' . $this->screen->location->organization_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'screen_id' => $this->screen->id,
            'status' => $this->screen->status,
            'last_ping_at' => $this->screen->last_ping_at,
        ];
    }
}

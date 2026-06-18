<?php

namespace App\Events;

use App\Models\Screen;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScreenOnline implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $screen;

    public function __construct(Screen $screen)
    {
        $this->screen = $screen;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('organization.' . $this->screen->organization_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'screen.online';
    }
}

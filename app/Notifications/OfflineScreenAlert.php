<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfflineScreenAlert extends Notification
{
    use Queueable;

    public $screen;

    public function __construct($screen)
    {
        $this->screen = $screen;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Screen Offline Alert: ' . $this->screen->name)
            ->line('Your screen "' . $this->screen->name . '" has gone offline.')
            ->line('We have not received a heartbeat from this device in the last 5 minutes.')
            ->action('View Screen Status', url('/screens'))
            ->line('Please check the device power and network connection.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'offline_screen_alert',
            'screen_id' => $this->screen->id,
            'screen_name' => $this->screen->name,
            'message' => "Screen '{$this->screen->name}' has gone offline.",
        ];
    }
}

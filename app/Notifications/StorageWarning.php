<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StorageWarning extends Notification
{
    use Queueable;

    public $organization;
    public $usagePercent;

    public function __construct($organization, int $usagePercent)
    {
        $this->organization = $organization;
        $this->usagePercent = $usagePercent;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Storage Capacity Warning: ' . $this->usagePercent . '% Used')
            ->line('Your organization "' . $this->organization->name . '" has used ' . $this->usagePercent . '% of its allocated storage space.')
            ->line('To ensure uninterrupted service and ability to upload new media, please consider upgrading your plan or removing old media assets.')
            ->action('Manage Storage', url('/settings'))
            ->line('Thank you for using NexView!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'storage_warning',
            'organization_id' => $this->organization->id,
            'usage_percent' => $this->usagePercent,
            'message' => "You have used {$this->usagePercent}% of your storage capacity.",
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketReply extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, TicketMessage $message)
    {
        $this->ticket = $ticket;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $senderName = $this->message->sender->name ?? 'Someone';
        
        // If notifiable is a PlatformUser (admin), route to admin panel
        if ($notifiable instanceof \App\Models\PlatformUser) {
            $url = route('admin.tickets.show', $this->ticket->id);
        } else {
            // Otherwise route to the user app
            $url = route('app.support.show', $this->ticket->id);
        }

        return (new MailMessage)
                    ->subject("New Reply on Ticket [{$this->ticket->id}]: {$this->ticket->subject}")
                    ->greeting("Hello {$notifiable->name},")
                    ->line("{$senderName} has replied to the ticket.")
                    ->line("Message snippet:")
                    ->line('"' . str()->limit($this->message->message, 100) . '"')
                    ->action('View Reply', $url)
                    ->line('Thank you for using NexView Support!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'message_id' => $this->message->id,
        ];
    }
}

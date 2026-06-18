<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketService
{
    /**
     * Create a new support ticket.
     */
    public function createTicket(array $data, $user): Ticket
    {
        $ticket = Ticket::create([
            'organization_id' => $user->organization_id,
            'user_id' => $user->id,
            'subject' => $data['subject'],
            'priority' => $data['priority'] ?? 'medium',
            'status' => 'open',
        ]);

        $this->addMessage($ticket, $user, $data['message'], $data['attachments'] ?? []);

        return $ticket;
    }

    /**
     * Add a message to an existing ticket.
     */
    public function addMessage(Ticket $ticket, $sender, string $message, array $attachments = []): TicketMessage
    {
        $ticketMessage = $ticket->messages()->create([
            'sender_type' => get_class($sender),
            'sender_id' => $sender->id,
            'message' => $message,
        ]);

        foreach ($attachments as $attachment) {
            $this->uploadAttachment($ticketMessage, $attachment);
        }

        // If a PlatformUser (Admin) replies, set status to in_progress if it was open.
        // If a normal User replies, we might want to change status to open if it was resolved/closed.
        if (get_class($sender) === \App\Models\PlatformUser::class) {
            if ($ticket->status === 'open') {
                $ticket->update(['status' => 'in_progress']);
            }
        } else {
             if (in_array($ticket->status, ['resolved', 'closed'])) {
                $ticket->update(['status' => 'open']);
             }
        }

        return $ticketMessage;
    }

    /**
     * Upload and attach a file to a message.
     */
    protected function uploadAttachment(TicketMessage $message, UploadedFile $file): void
    {
        $path = $file->store('tickets/attachments', 'public');

        $message->attachments()->create([
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    /**
     * Change ticket status.
     */
    public function updateStatus(Ticket $ticket, string $status): bool
    {
        return $ticket->update(['status' => $status]);
    }

    /**
     * Change ticket priority.
     */
    public function updatePriority(Ticket $ticket, string $priority): bool
    {
        return $ticket->update(['priority' => $priority]);
    }
}

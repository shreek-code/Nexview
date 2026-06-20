<?php

namespace App\Livewire\Admin\Tickets;

use App\Models\Ticket;
use App\Services\TicketService;
use Livewire\Component;
use Livewire\WithFileUploads;

class TicketShow extends Component
{
    use WithFileUploads;

    public Ticket $ticket;
    public $message = '';
    public $attachments = [];
    public $newStatus;

    protected $rules = [
        'message' => 'required|min:2',
        'attachments.*' => 'nullable|file|max:10240',
        'newStatus' => 'required|in:open,in_progress,resolved,closed',
    ];

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->newStatus = $ticket->status;

        // Mark any notifications for this ticket as read
        auth('platform')->user()->unreadNotifications()
            ->where('data->ticket_id', $ticket->id)
            ->get()
            ->markAsRead();
    }

    public function updateStatus(TicketService $ticketService)
    {
        $this->validateOnly('newStatus');
        $ticketService->updateStatus($this->ticket, $this->newStatus);
        
        session()->flash('status_message', 'Ticket status updated to ' . str_replace('_', ' ', $this->newStatus) . '.');
        $this->ticket->refresh();
    }

    public function reply(TicketService $ticketService)
    {
        $this->validate([
            'message' => 'required|min:2',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $ticketService->addMessage(
            $this->ticket,
            auth('platform')->user(),
            $this->message,
            $this->attachments
        );

        $this->message = '';
        $this->attachments = [];
        
        $this->ticket->refresh();
        $this->newStatus = $this->ticket->status;
        session()->flash('message', 'Reply sent successfully.');
    }

    public function render()
    {
        $messages = $this->ticket->messages()->with('sender', 'attachments')->orderBy('created_at', 'asc')->get();

        return view('livewire.admin.tickets.ticket-show', [
            'messages' => $messages,
        ]);
    }
}

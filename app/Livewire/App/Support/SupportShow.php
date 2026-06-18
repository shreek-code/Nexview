<?php

namespace App\Livewire\App\Support;

use App\Models\Ticket;
use App\Services\TicketService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Support Ticket')]
class SupportShow extends Component
{
    use WithFileUploads;

    public Ticket $ticket;
    public $message = '';
    public $attachments = [];

    protected $rules = [
        'message' => 'required|min:2',
        'attachments.*' => 'nullable|file|max:10240',
    ];

    public function mount(Ticket $ticket)
    {
        // Ensure the ticket belongs to the user's organization
        if ($ticket->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Unauthorized access to ticket.');
        }

        $this->ticket = $ticket;
    }

    public function reply(TicketService $ticketService)
    {
        $this->validate();

        $ticketService->addMessage(
            $this->ticket,
            auth()->user(),
            $this->message,
            $this->attachments
        );

        $this->message = '';
        $this->attachments = [];
        
        $this->ticket->refresh();
        session()->flash('message', 'Reply sent successfully.');
    }

    public function render()
    {
        $messages = $this->ticket->messages()->with('sender', 'attachments')->orderBy('created_at', 'asc')->get();

        return view('livewire.app.support.support-show', [
            'messages' => $messages,
        ]);
    }
}

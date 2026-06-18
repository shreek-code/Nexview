<?php

namespace App\Livewire\App\Support;

use App\Services\TicketService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Create Support Ticket')]
class SupportCreate extends Component
{
    use WithFileUploads;

    public $subject = '';
    public $message = '';
    public $priority = 'medium';
    public $attachments = [];

    protected $rules = [
        'subject' => 'required|min:5|max:255',
        'message' => 'required|min:10',
        'priority' => 'required|in:low,medium,high',
        'attachments.*' => 'nullable|file|max:10240', // 10MB Max per file
    ];

    public function submit(TicketService $ticketService)
    {
        $this->validate();

        $ticket = $ticketService->createTicket([
            'subject' => $this->subject,
            'message' => $this->message,
            'priority' => $this->priority,
            'attachments' => $this->attachments,
        ], auth()->user());

        session()->flash('message', 'Support ticket created successfully.');

        return redirect()->route('app.support.show', $ticket);
    }

    public function render()
    {
        return view('livewire.app.support.support-create');
    }
}

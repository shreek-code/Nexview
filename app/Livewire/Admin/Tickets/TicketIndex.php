<?php

namespace App\Livewire\Admin\Tickets;

use App\Models\Ticket;
use Livewire\Component;

class TicketIndex extends Component
{
    public function render()
    {
        $tickets = Ticket::with('organization', 'user')
            ->orderByRaw("FIELD(status, 'open', 'in_progress', 'resolved', 'closed')")
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('livewire.admin.tickets.ticket-index', [
            'tickets' => $tickets,
        ]);
    }
}

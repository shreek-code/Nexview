<?php

namespace App\Livewire\App\Support;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Support Tickets')]
class SupportIndex extends Component
{
    public function render()
    {
        $tickets = auth()->user()->organization->tickets()->orderBy('updated_at', 'desc')->get();

        return view('livewire.app.support.support-index', [
            'tickets' => $tickets,
        ]);
    }
}

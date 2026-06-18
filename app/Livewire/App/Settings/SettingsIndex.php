<?php

namespace App\Livewire\App\Settings;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Account Settings')]
class SettingsIndex extends Component
{
    public $currentTab = 'general';

    public function setTab($tab)
    {
        $this->currentTab = $tab;
    }

    public function render()
    {
        return view('livewire.app.settings.settings-index', [
            'organization' => auth()->user()->organization,
            'subscription' => auth()->user()->organization->subscription()->first(),
        ]);
    }
}

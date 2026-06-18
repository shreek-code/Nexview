<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;

class SettingsIndex extends Component
{
    public $appName = 'NexView';
    public $supportEmail = 'support@nexview.com';
    public $maintenanceMode = false;
    public $allowRegistration = true;

    public function save()
    {
        // In a real app, we would save to a settings table or config.
        // For now, we just show a success message.
        session()->flash('success', 'Platform settings have been updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.settings.settings-index');
    }
}

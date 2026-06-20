<?php

namespace App\Livewire\App\Settings;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Billing & Subscriptions')]
class Billing extends Component
{
    public function render()
    {
        $organization = Auth::user()->organization;
        $subscription = $organization->subscription()->with('plan')->first();
        $plan = $subscription?->plan;

        return view('livewire.app.settings.billing', [
            'organization' => $organization,
            'subscription' => $subscription,
            'plan' => $plan,
        ]);
    }
}

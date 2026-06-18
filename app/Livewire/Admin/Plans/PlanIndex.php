<?php

namespace App\Livewire\Admin\Plans;

use Livewire\Component;

use App\Models\Plan;

class PlanIndex extends Component
{
    public function render()
    {
        $plans = Plan::orderBy('name', 'asc')->get();
        return view('livewire.admin.plans.plan-index', compact('plans'));
    }
}

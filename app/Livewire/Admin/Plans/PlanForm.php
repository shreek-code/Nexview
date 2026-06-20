<?php

namespace App\Livewire\Admin\Plans;

use Livewire\Component;
use App\Models\Plan;
use Illuminate\Support\Str;

class PlanForm extends Component
{
    public ?Plan $plan = null;

    public $name;
    public $slug;
    public $price_inr = 0;
    public $price_period;
    public $payment_model;
    public $billing_cycle;
    public $remote_access = true;
    public $network_restriction;
    public $is_active = true;

    // JSON Arrays
    public $limits = [];
    public $analytics = [];
    public $widgets = [];
    public $broadcasts = [];

    public function mount(?Plan $plan = null)
    {
        if ($plan && $plan->exists) {
            $this->plan = $plan;
            $this->name = $plan->name;
            $this->slug = $plan->slug;
            $this->price_inr = $plan->price_inr;
            $this->price_period = $plan->price_period;
            $this->payment_model = $plan->payment_model;
            $this->billing_cycle = $plan->billing_cycle;
            $this->remote_access = $plan->remote_access;
            $this->network_restriction = $plan->network_restriction;
            $this->is_active = $plan->is_active;
            
            $this->limits = $plan->limits ?? [];
            $this->analytics = $plan->analytics ?? [];
            $this->widgets = $plan->widgets ?? [];
            $this->broadcasts = $plan->broadcasts ?? [];
        } else {
            $this->limits = [
                'screens' => ['unlimited' => false, 'min' => null, 'max' => 10],
                'locations' => 0,
                'storage_gb' => 10,
                'managers' => 0,
            ];
            $this->analytics = [
                'proof_of_play' => false,
                'screen_uptime' => false,
                'campaign_delivery_report' => false,
                'media_performance' => false,
                'export_csv_pdf' => false,
            ];
            $this->widgets = [
                'clock' => false,
                'static_rss' => false,
                'date_display' => false,
                'weather' => false,
                'live_rss' => false,
                'alert_display' => false,
                'social_feeds' => false,
                'custom_data' => false,
            ];
            $this->broadcasts = [
                'manual_override' => false,
                'automated_alert_rules' => false,
            ];
        }
    }

    public function updatedName($value)
    {
        if (!$this->plan || !$this->plan->exists) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . ($this->plan ? $this->plan->id : 'NULL'),
            'price_inr' => 'required|integer|min:0',
            'price_period' => 'nullable|string|in:per_screen_month,per_screen_year,flat_monthly,flat_yearly,one_time',
            'payment_model' => 'nullable|string',
            'billing_cycle' => 'nullable|string',
            'remote_access' => 'boolean',
            'network_restriction' => 'nullable|string',
            'is_active' => 'boolean',
            'limits' => 'array',
            'analytics' => 'array',
            'widgets' => 'array',
            'broadcasts' => 'array',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'price_inr' => $this->price_inr,
            'price_period' => $this->price_period,
            'payment_model' => $this->payment_model,
            'billing_cycle' => $this->billing_cycle,
            'remote_access' => $this->remote_access,
            'network_restriction' => $this->network_restriction,
            'is_active' => $this->is_active,
            'limits' => $this->limits,
            'analytics' => $this->analytics,
            'widgets' => $this->widgets,
            'broadcasts' => $this->broadcasts,
        ];

        if ($this->plan && $this->plan->exists) {
            $this->plan->update($data);
            session()->flash('success', 'Plan updated successfully.');
        } else {
            Plan::create($data);
            session()->flash('success', 'Plan created successfully.');
        }

        return redirect()->route('admin.plans.index');
    }

    public function render()
    {
        return view('livewire.admin.plans.plan-form');
    }
}

<?php

namespace App\Livewire\App;

use Livewire\Component;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use App\Models\Subscription;
use Carbon\Carbon;

class Onboarding extends Component
{
    public $step = 1;

    // Step 1: Basic Questions
    public $phone;
    public $company_size;
    public $industry;

    // Selected Plan Info (from session if they came from Pricing)
    public $selectedPlanId;
    public $selectedCycle;
    public $planDetails;

    // Razorpay Data
    public $razorpayOrderId;
    public $amountToPay = 0;
    
    // For JS to handle Razorpay callback
    public $paymentId;
    public $paymentSignature;

    protected $listeners = ['paymentSuccess' => 'handlePaymentSuccess'];

    public function mount(\Illuminate\Http\Request $request)
    {
        $organization = Auth::user()->organization;

        // If onboarded AND has an active subscription, kick them out
        if ($organization->is_onboarded && $organization->subscription && $organization->subscription->isActive()) {
            return redirect()->route('app.dashboard');
        }

        // Load plan from query param or session if available
        $this->selectedPlanId = $request->query('plan_id', session('selected_plan_id', 'starter'));
        $this->selectedCycle = $request->query('cycle', session('selected_plan_cycle', 'monthly'));

        $this->loadPlanDetails();

        if ($organization->is_onboarded) {
            $this->step = 2;
            $this->createRazorpayOrder();
        }
    }

    public function loadPlanDetails()
    {
        $this->planDetails = Plan::where('id', $this->selectedPlanId)->first();
        if ($this->planDetails) {
            $this->amountToPay = $this->selectedCycle === 'yearly' 
                ? $this->planDetails->price_inr_yearly 
                : $this->planDetails->price_inr_monthly;
        }
    }

    public function saveBasicInfo()
    {
        $this->validate([
            'phone' => 'required|string|max:20',
            'company_size' => 'required|string|max:50',
            'industry' => 'required|string|max:100',
        ]);

        $organization = Auth::user()->organization;
        $organization->update([
            'phone' => $this->phone,
            'company_size' => $this->company_size,
            'industry' => $this->industry,
        ]);

        // Proceed to payment step
        $this->step = 2;
        $this->createRazorpayOrder();
    }

    public function createRazorpayOrder()
    {
        if ($this->amountToPay <= 0) {
            // Free plan or 100% discount, skip payment
            return;
        }

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $orderData = [
            'receipt'         => 'rcptid_' . Auth::user()->organization->id . '_' . time(),
            'amount'          => $this->amountToPay * 100, // Amount in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        try {
            $razorpayOrder = $api->order->create($orderData);
            $this->razorpayOrderId = $razorpayOrder['id'];
            
            // Dispatch event to trigger Razorpay checkout via Alpine.js
            $this->dispatch('init-razorpay', [
                'key' => env('RAZORPAY_KEY'),
                'amount' => $orderData['amount'],
                'currency' => $orderData['currency'],
                'name' => 'NexView',
                'description' => 'Subscription: ' . ($this->planDetails->name ?? 'Plan'),
                'order_id' => $this->razorpayOrderId,
                'prefill' => [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'contact' => $this->phone,
                ]
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Could not initialize payment gateway: ' . $e->getMessage());
        }
    }

    public function handlePaymentSuccess($paymentId, $orderId, $signature)
    {
        try {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $attributes = [
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // Signature verified successfully
            $this->activateSubscription();

        } catch (\Exception $e) {
            session()->flash('error', 'Payment verification failed.');
        }
    }

    public function activateFreePlan()
    {
        if ($this->amountToPay > 0) {
            session()->flash('error', 'Invalid operation.');
            return;
        }

        $this->activateSubscription();
    }

    protected function activateSubscription()
    {
        $organization = Auth::user()->organization;

        // Calculate ends_at
        $endsAt = Carbon::now();
        if ($this->selectedCycle === 'yearly') {
            $endsAt->addYear();
        } else {
            $endsAt->addMonth();
        }

        Subscription::create([
            'organization_id' => $organization->id,
            'plan_id' => $this->selectedPlanId ?? 'starter',
            'status' => 'active',
            'trial_ends_at' => null, // No trial based on feedback
            'ends_at' => $endsAt,
        ]);

        $organization->update(['is_onboarded' => true]);

        // Clean up session
        session()->forget(['selected_plan_id', 'selected_plan_cycle']);

        return redirect()->route('app.dashboard');
    }

    public function render()
    {
        return view('livewire.app.onboarding')->layout('components.layouts.guest');
    }
}

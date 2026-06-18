<div>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[#0B0F19] relative overflow-hidden">
        
        <!-- Background Effects -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-purple-600/20 blur-[120px] mix-blend-screen"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-600/20 blur-[120px] mix-blend-screen"></div>
        </div>

        <div class="max-w-md w-full space-y-8 z-10">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white tracking-tight">
                    @if($step === 1) Basic Information @else Complete Payment @endif
                </h1>
                <p class="text-white/60 text-sm mt-2">
                    @if($step === 1) Tell us a bit about your organization @else Secure payment via Razorpay @endif
                </p>
            </div>

            <div class="bg-white/[0.02] backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl p-8">
                
                @if (session()->has('error'))
                    <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if($step === 1)
                    <form wire:submit.prevent="saveBasicInfo" class="space-y-5">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-white/70 text-xs font-semibold uppercase tracking-wider mb-2">Phone Number</label>
                            <input id="phone" type="text" wire:model="phone" required
                                class="block w-full rounded-2xl bg-white/[0.04] border border-white/10 text-white px-4 py-3.5 focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 focus:bg-white/[0.08] transition-all duration-300 placeholder:text-white/20 outline-none text-sm" 
                                placeholder="+1 234 567 8900">
                            @error('phone') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Industry -->
                        <div>
                            <label for="industry" class="block text-white/70 text-xs font-semibold uppercase tracking-wider mb-2">Industry</label>
                            <select id="industry" wire:model="industry" required
                                class="block w-full rounded-2xl bg-white/[0.04] border border-white/10 text-white px-4 py-3.5 focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 focus:bg-white/[0.08] transition-all duration-300 outline-none text-sm [&>option]:bg-[#131728]">
                                <option value="">Select Industry</option>
                                <option value="retail">Retail</option>
                                <option value="hospitality">Hospitality</option>
                                <option value="corporate">Corporate</option>
                                <option value="education">Education</option>
                                <option value="healthcare">Healthcare</option>
                                <option value="other">Other</option>
                            </select>
                            @error('industry') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Company Size -->
                        <div>
                            <label for="company_size" class="block text-white/70 text-xs font-semibold uppercase tracking-wider mb-2">Company Size</label>
                            <select id="company_size" wire:model="company_size" required
                                class="block w-full rounded-2xl bg-white/[0.04] border border-white/10 text-white px-4 py-3.5 focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 focus:bg-white/[0.08] transition-all duration-300 outline-none text-sm [&>option]:bg-[#131728]">
                                <option value="">Select Size</option>
                                <option value="1-10">1-10 employees</option>
                                <option value="11-50">11-50 employees</option>
                                <option value="51-200">51-200 employees</option>
                                <option value="201+">201+ employees</option>
                            </select>
                            @error('company_size') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-2xl shadow-lg shadow-purple-500/10 hover:shadow-purple-500/25 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-[#070714] transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 cursor-pointer">
                                Continue to Payment
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            </button>
                        </div>
                    </form>
                @endif

                @if($step === 2)
                    <div class="space-y-6">
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-center">
                            <p class="text-white/60 text-sm mb-1">Selected Plan</p>
                            <h3 class="text-xl font-bold text-white capitalize">{{ $planDetails->name ?? 'Plan' }}</h3>
                            <p class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400 mt-2">
                                ₹{{ number_format($amountToPay, 2) }}
                            </p>
                            <p class="text-white/50 text-xs mt-1 capitalize">Billed {{ $selectedCycle }}</p>
                        </div>

                        @if($amountToPay > 0)
                            <div class="pt-4" x-data="razorpayIntegration()" x-init="initPayment" @init-razorpay.window="openCheckout($event.detail)">
                                <button id="rzp-button" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-2xl shadow-lg shadow-purple-500/10 hover:shadow-purple-500/25 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-[#070714] transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                    Pay Securely
                                </button>
                                <p class="text-center text-xs text-white/40 mt-3">Payments are processed securely by Razorpay.</p>
                            </div>
                        @else
                            <div class="pt-4">
                                <button wire:click="activateFreePlan" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-2xl shadow-lg shadow-purple-500/10 hover:shadow-purple-500/25 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-[#070714] transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 cursor-pointer">
                                    Activate Free Plan
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('razorpayIntegration', () => ({
                openCheckout(options) {
                    var rzpOptions = {
                        "key": options.key,
                        "amount": options.amount,
                        "currency": options.currency,
                        "name": options.name,
                        "description": options.description,
                        "order_id": options.order_id,
                        "handler": function (response){
                            // Call Livewire component method
                            @this.handlePaymentSuccess(
                                response.razorpay_payment_id, 
                                response.razorpay_order_id, 
                                response.razorpay_signature
                            );
                        },
                        "prefill": {
                            "name": options.prefill.name,
                            "email": options.prefill.email,
                            "contact": options.prefill.contact
                        },
                        "theme": {
                            "color": "#9333ea"
                        }
                    };
                    var rzp1 = new Razorpay(rzpOptions);
                    rzp1.on('payment.failed', function (response){
                        alert(response.error.description);
                    });
                    
                    document.getElementById('rzp-button').onclick = function(e){
                        rzp1.open();
                        e.preventDefault();
                    }
                }
            }))
        })
    </script>
    @endpush
</div>

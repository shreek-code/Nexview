<x-layouts.guest>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white tracking-tight">Verify Email</h1>
        <p class="text-white/60 text-sm mt-3 leading-relaxed">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-sm text-emerald-300 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>A new verification link has been sent to the email address you provided during registration.</span>
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-2xl shadow-lg shadow-purple-500/10 hover:shadow-purple-500/25 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-[#070714] transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 cursor-pointer">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-2xl border border-white/10 text-sm font-semibold text-white/70 hover:text-white hover:bg-white/5 transition-all duration-300 cursor-pointer">
                Log Out
            </button>
        </form>
    </div>
</x-layouts.guest>

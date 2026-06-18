<x-layouts.guest>
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-sm text-emerald-300 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white tracking-tight">Forgot Password</h1>
        <p class="text-white/60 text-sm mt-2">
            No problem. Let us know your email address and we will email you a password reset link.
        </p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-white/70 text-xs font-semibold uppercase tracking-wider mb-2">Email Address</label>
            <div class="relative">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="block w-full rounded-2xl bg-white/[0.04] border border-white/10 text-white px-4 py-3.5 focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 focus:bg-white/[0.08] transition-all duration-300 placeholder:text-white/20 outline-none text-sm" 
                    placeholder="name@company.com">
            </div>
            @error('email')
                <div class="text-xs text-red-400 mt-2 flex items-center gap-2 bg-red-500/10 border border-red-500/20 px-3.5 py-2.5 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 flex-shrink-0"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-2xl shadow-lg shadow-purple-500/10 hover:shadow-purple-500/25 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-[#070714] transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 cursor-pointer">
                Email Password Reset Link
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
            </button>
        </div>
        
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="font-semibold text-purple-400 hover:text-purple-300 transition-colors text-sm">
                Back to Sign In
            </a>
        </div>
    </form>
</x-layouts.guest>

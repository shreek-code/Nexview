<x-layouts.guest>
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-sm text-emerald-300 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white tracking-tight">Welcome Back</h1>
        <p class="text-white/60 text-sm mt-2">Enter your credentials to access NexView</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-white/70 text-xs font-semibold uppercase tracking-wider mb-2">Email Address</label>
            <div class="relative">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
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

        <!-- Password -->
        <div x-data="{ show: false }">
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-white/70 text-xs font-semibold uppercase tracking-wider">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-purple-400 hover:text-purple-300 transition-colors" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            
            <div class="relative">
                <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password"
                    class="block w-full rounded-2xl bg-white/[0.04] border border-white/10 text-white px-4 py-3.5 pr-12 focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 focus:bg-white/[0.08] transition-all duration-300 placeholder:text-white/20 outline-none text-sm" 
                    placeholder="••••••••">
                
                <button type="button" @click="show = !show" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-white/40 hover:text-white/80 focus:outline-none transition-colors p-1.5 rounded-lg hover:bg-white/5">
                    <!-- Eye Off (Hidden state) -->
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                    <!-- Eye (Visible state) -->
                    <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </button>
            </div>
            @error('password')
                <div class="text-xs text-red-400 mt-2 flex items-center gap-2 bg-red-500/10 border border-red-500/20 px-3.5 py-2.5 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 flex-shrink-0"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="flex items-center cursor-pointer group">
                <div class="relative flex items-center justify-center">
                    <input id="remember_me" type="checkbox" name="remember" class="peer sr-only">
                    <div class="w-5 h-5 rounded-lg border border-white/20 bg-white/5 peer-checked:bg-gradient-to-r peer-checked:from-purple-600 peer-checked:to-indigo-600 peer-checked:border-transparent transition-all flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                </div>
                <span class="ms-3 text-sm text-white/60 group-hover:text-white/80 transition-colors">Remember me</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-2xl shadow-lg shadow-purple-500/10 hover:shadow-purple-500/25 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-[#070714] transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 cursor-pointer">
                Sign In
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
            </button>
        </div>
        
        <div class="text-center mt-6">
            <p class="text-sm text-white/40">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-semibold text-purple-400 hover:text-purple-300 transition-colors">Sign up for free</a>
            </p>
        </div>
    </form>
</x-layouts.guest>

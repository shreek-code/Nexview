<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NexView') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        
        
        <style>
            [x-cloak] { display: none !important; }
            @keyframes float-1 {
                0%, 100% { transform: translate(0, 0) scale(1); }
                50% { transform: translate(8%, 12%) scale(1.15); }
            }
            @keyframes float-2 {
                0%, 100% { transform: translate(0, 0) scale(1); }
                50% { transform: translate(-12%, -8%) scale(0.9); }
            }
            @keyframes float-3 {
                0%, 100% { transform: translate(0, 0) scale(1); }
                50% { transform: translate(10%, -10%) scale(1.05); }
            }
            .animate-orb-1 { animation: float-1 25s infinite ease-in-out; }
            .animate-orb-2 { animation: float-2 30s infinite ease-in-out; }
            .animate-orb-3 { animation: float-3 28s infinite ease-in-out; }
        </style>
    </head>
    <body class="font-sans antialiased relative min-h-screen flex items-center justify-center overflow-hidden bg-[#070714]">
        
        <!-- Vibrant Background -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <!-- Abstract Shapes -->
            <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-indigo-600 blur-[130px] opacity-35 mix-blend-screen animate-orb-1"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[60vw] h-[60vw] rounded-full bg-purple-600 blur-[160px] opacity-25 mix-blend-screen animate-orb-2"></div>
            <div class="absolute top-[20%] right-[10%] w-[40vw] h-[40vw] rounded-full bg-cyan-500 blur-[110px] opacity-15 mix-blend-screen animate-orb-3"></div>
        </div>

        <div class="relative z-10 w-full max-w-md px-6 py-12">
            <div class="flex justify-center mb-8">
                <a href="/" class="flex items-center gap-3 drop-shadow-lg transition-transform hover:scale-105">
                    <x-application-logo class="h-12 w-12 fill-current text-white" />
                    <span class="text-3xl font-bold text-white tracking-tight">NexView</span>
                </a>
            </div>

            <div class="w-full bg-white/[0.06] backdrop-blur-2xl border border-white/10 px-8 py-10 shadow-[0_12px_40px_0_rgba(0,0,0,0.5)] rounded-[2rem]">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-sm text-white/50">
                &copy; {{ date('Y') }} NexView Platform. All rights reserved.
            </div>
        </div>
        
        @livewireScripts
    </body>
</html>

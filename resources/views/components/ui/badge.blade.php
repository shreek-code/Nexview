@props([
    'variant' => 'default',
    'showDot' => false,
])

@php
    $baseClass = "inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2";

    $variants = [
        'default' => 'border-transparent bg-primary text-primary-foreground hover:bg-primary/80',
        'secondary' => 'border-transparent bg-surface-2 text-text-primary hover:bg-surface-3',
        'destructive' => 'border-transparent bg-red-500 text-white hover:bg-red-500/80',
        'outline' => 'text-text-primary border-border-base',
        'success' => 'border-transparent bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500/20',
        'warning' => 'border-transparent bg-amber-500/10 text-amber-500 hover:bg-amber-500/20',
        'online' => 'border-emerald-500/20 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 shadow-glow-success',
        'offline' => 'border-red-500/20 bg-red-500/10 text-red-600 dark:text-red-400 shadow-glow-error',
        'draft' => 'border-border-base bg-surface-2 text-text-secondary',
        'active' => 'border-signal-500/20 bg-signal-500/10 text-signal-600 dark:text-signal-400 shadow-glow-signal',
        'info' => 'border-transparent bg-blue-500/10 text-blue-500 hover:bg-blue-500/20',
    ];

    $classes = $baseClass . ' ' . $variants[$variant];
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if ($showDot)
        @php
            $dotColor = 'bg-gray-500';
            if ($variant === 'online' || $variant === 'success') $dotColor = 'bg-emerald-500';
            if ($variant === 'offline' || $variant === 'destructive') $dotColor = 'bg-red-500';
            if ($variant === 'warning') $dotColor = 'bg-amber-500';
            if ($variant === 'active') $dotColor = 'bg-signal-500';
            if ($variant === 'info') $dotColor = 'bg-blue-500';
        @endphp
        <span class="mr-1.5 flex h-2 w-2 rounded-full {{ $dotColor }}"></span>
    @endif
    {{ $slot }}
</div>

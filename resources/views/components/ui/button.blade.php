@props([
    'variant' => 'default',
    'size' => 'default',
    'type' => 'button',
    'href' => null,
])

@php
    $baseClass = "inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50";

    $variants = [
        'default' => 'bg-signal-600 text-white hover:bg-signal-600/90 shadow-sm hover:shadow-md',
        'destructive' => 'bg-red-500 text-white hover:bg-red-500/90 shadow-sm hover:shadow-md',
        'outline' => 'border border-border-base bg-surface-1 hover:bg-surface-2 text-text-primary',
        'secondary' => 'bg-surface-2 text-text-primary hover:bg-surface-3',
        'ghost' => 'hover:bg-surface-2 text-text-primary',
        'link' => 'text-signal-600 underline-offset-4 hover:underline',
    ];

    $sizes = [
        'default' => 'h-10 px-4 py-2',
        'sm' => 'h-9 rounded-md px-3',
        'lg' => 'h-11 rounded-md px-8',
        'icon' => 'h-10 w-10',
    ];

    $classes = $baseClass . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

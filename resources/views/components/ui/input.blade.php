@props(['disabled' => false, 'type' => 'text'])

@if($type === 'password')
<div x-data="{ show: false }" class="relative w-full">
    <input {{ $disabled ? 'disabled' : '' }} :type="show ? 'text' : 'password'" {!! $attributes->merge(['class' => 'flex h-10 w-full rounded-md border border-border-base bg-surface-1 px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-text-tertiary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-signal-500 focus-visible:border-signal-500 disabled:cursor-not-allowed disabled:opacity-50 transition-colors shadow-sm pr-10']) !!}>
    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-text-tertiary hover:text-text-primary focus:outline-none">
        <x-heroicon-o-eye-slash x-show="!show" class="h-4 w-4" />
        <x-heroicon-o-eye x-show="show" x-cloak class="h-4 w-4" />
    </button>
</div>
@else
<input type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'flex h-10 w-full rounded-md border border-border-base bg-surface-1 px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-text-tertiary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-signal-500 focus-visible:border-signal-500 disabled:cursor-not-allowed disabled:opacity-50 transition-colors shadow-sm']) !!}>
@endif

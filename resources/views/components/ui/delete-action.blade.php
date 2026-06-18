@props(['action', 'confirmText' => 'Are you sure you want to delete this item?', 'as' => 'button'])

@if($as === 'dropdown-link')
    <x-ui.dropdown-link as="button" type="button" wire:click="{{ $action }}" wire:confirm="{{ $confirmText }}" {{ $attributes->merge(['class' => 'text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20']) }}>
        {{ $slot }}
    </x-ui.dropdown-link>
@else
    <button type="button" wire:click="{{ $action }}" wire:confirm="{{ $confirmText }}" {{ $attributes->merge(['class' => 'inline-flex items-center text-red-600 hover:text-red-700 transition-colors']) }}>
        {{ $slot }}
    </button>
@endif

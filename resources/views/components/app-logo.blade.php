@props([
    'sidebar' => false,
])


@if($sidebar)
    <flux:sidebar.brand name="{{ Auth::user()->name }}" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Finance Tracker Logo" class="size-8" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="{{ Auth::user()->name }}" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Finance Tracker Logo" class="size-8" />
        </x-slot>
    </flux:brand>
@endif

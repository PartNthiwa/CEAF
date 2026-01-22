@props([
    'sidebar' => false,
])

@php
 
    $dashboardRoute = route('admin.dashboard');
@endphp

@if($sidebar)
    <flux:sidebar.brand name="CEAF Benovelent" href="{{ $dashboardRoute }}" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="CEAF Benovelent" href="{{ $dashboardRoute }}" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white" />
        </x-slot>
    </flux:brand>
@endif

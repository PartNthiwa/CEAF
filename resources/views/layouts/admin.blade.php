<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <title>Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

@php
    abort_unless(auth()->check(), 401);
@endphp

<body class="bg-gray-100 font-sans min-h-screen">

<div x-data="{ open: false }" class="min-h-screen">

    {{-- DESKTOP SIDEBAR --}}
    <aside class="hidden lg:flex fixed inset-y-0 left-0 w-64 bg-gray-800 text-gray-200 border-r flex-col z-40">
        <div class="px-6 py-4 flex items-center justify-between border-b bg-blue-900">
            <x-app-logo />
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-green-600 font-semibold' : '' }}">
                Dashboard
            </a>

            {{-- Members --}}
            <div x-data="{ open: {{ request()->routeIs('admin.members') || request()->routeIs('admin.dependents') || request()->routeIs('admin.beneficiaries') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.members') || request()->routeIs('admin.dependents') || request()->routeIs('admin.beneficiaries') ? 'bg-green-600 font-semibold' : '' }}">
                    Members
                    <i :class="{'rotate-90': open}" class="fas fa-chevron-right"></i>
                </button>

                <div x-show="open" x-transition class="ml-4 mt-1 space-y-1">
                  
                    <a href="{{ route('admin.members-list') }}"
                            class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.members-list') ? 'bg-green-700 font-semibold' : '' }}">
                        Members List
                    </a>
                    <a href="{{ route('admin.dependents') }}"
                       class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dependents') ? 'bg-green-700 font-semibold' : '' }}">
                        Dependents
                    </a>

                    <a href="{{ route('admin.beneficiaries') }}"
                       class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.beneficiaries') ? 'bg-green-700 font-semibold' : '' }}">
                        Beneficiaries
                    </a>
                </div>
            </div>

            {{-- Beneficiaries --}}
            <div x-data="{ open: {{ request()->routeIs('admin.beneficiary.list') || request()->routeIs('admin.beneficiary.requests') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.beneficiary.list') || request()->routeIs('admin.beneficiary.requests') ? 'bg-green-600 font-semibold' : '' }}">
                    Beneficiaries
                    <i :class="{'rotate-90': open}" class="fas fa-chevron-right"></i>
                </button>

                <div x-show="open" x-transition class="ml-4 mt-1 space-y-1">
                  
                    <a href="{{ route('admin.beneficiary.requests') }}"
                       class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.beneficiary.requests') ? 'bg-green-700 font-semibold' : '' }}">
                        Request List
                    </a>
                </div>
            </div>

            {{-- Accounting --}}
            <div x-data="{ open: {{ request()->routeIs('admin.payments') || request()->routeIs('admin.seed-cycle') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.payments') || request()->routeIs('admin.seed-cycle') ? 'bg-green-600 font-semibold' : '' }}">
                    Accounting
                    <i :class="{'rotate-90': open}" class="fas fa-chevron-right"></i>
                </button>

                <div x-show="open" x-transition class="ml-4 mt-1 space-y-1">
                    <a href="{{ route('admin.payments') }}"
                       class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.payments') ? 'bg-green-700 font-semibold' : '' }}">
                        Payments
                    </a>

                    <a href="{{ route('admin.seed-cycle') }}"
                       class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.seed-cycle') ? 'bg-green-700 font-semibold' : '' }}">
                        Seed cycle
                    </a>
                </div>
            </div>

            {{-- Settings --}}
            <div x-data="{ open: {{ request()->routeIs('admin.settings') || request()->routeIs('admin.seed-cycle') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.settings') || request()->routeIs('admin.seed-cycle') ? 'bg-green-600 font-semibold' : '' }}">
                   Setting
                    <i :class="{'rotate-90': open}" class="fas fa-chevron-right"></i>
                </button>

                <div x-show="open" x-transition class="ml-4 mt-1 space-y-1">
                    <a href="{{ route('admin.configuration') }}"
                       class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.configuration') ? 'bg-green-700 font-semibold' : '' }}">
                        Configuration
                    </a>
                </div>
            </div>

        </nav>

        <div class="px-6 py-4 border-t">
            <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
            <div class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="text-red-500 text-sm hover:underline w-full text-left">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MOBILE MENU --}}
    <div class="lg:hidden">
        <div class="flex items-center justify-between bg-gray-800 text-gray-200 px-4 py-3">
            <button @click="open = true" class="text-white">
                <i class="fas fa-bars"></i>
            </button>

            <div class="flex items-center gap-2">
                <x-app-logo class="w-8 h-8" />
                <span class="font-bold">Admin</span>
            </div>
        </div>

        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex">
            <div class="fixed inset-0 bg-black opacity-50" @click="open = false"></div>

            <div class="relative w-72 bg-gray-800 text-white min-h-screen">
                <div class="flex items-center justify-between px-4 py-4 border-b">
                    <div class="font-bold">Menu</div>
                    <button @click="open = false" class="text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <nav class="px-4 py-4 space-y-2">

                    <a href="{{ route('admin.dashboard') }}" @click="open=false"
                       class="block px-3 py-2 rounded text-white hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-green-600 font-semibold' : '' }}">
                        Dashboard
                    </a>

                    {{-- Members Dropdown --}}
                    <div x-data="{ openSub: {{ request()->routeIs('admin.members') || request()->routeIs('admin.dependents') || request()->routeIs('admin.beneficiaries') ? 'true' : 'false' }} }">
                        <button @click="openSub = !openSub"
                                class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-700">
                            Members
                            <i :class="{'rotate-90': openSub}" class="fas fa-chevron-right"></i>
                        </button>

                        <div x-show="openSub" x-transition class="ml-4 mt-1 space-y-1">
                           
                            <a href="{{ route('admin.dependents') }}" @click="open=false"
                               class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dependents') ? 'bg-green-600 font-semibold' : '' }}">
                                Dependents
                            </a>

                            <a href="{{ route('admin.beneficiaries') }}" @click="open=false"
                               class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.beneficiaries') ? 'bg-green-600 font-semibold' : '' }}">
                                Beneficiaries
                            </a>
                        </div>
                    </div>

                    {{-- Beneficiaries Dropdown --}}
                    <div x-data="{ openSub2: {{ request()->routeIs('admin.beneficiary.list') || request()->routeIs('admin.beneficiary.requests') ? 'true' : 'false' }} }">
                        <button @click="openSub2 = !openSub2"
                                class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-700">
                            Beneficiaries
                            <i :class="{'rotate-90': openSub2}" class="fas fa-chevron-right"></i>
                        </button>

                        <div x-show="openSub2" x-transition class="ml-4 mt-1 space-y-1">
                           

                            <a href="{{ route('admin.beneficiary.requests') }}" @click="open=false"
                               class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.beneficiary.requests') ? 'bg-green-600 font-semibold' : '' }}">
                                Request List
                            </a>
                        </div>
                    </div>

                    {{-- Accounting --}}
                    <div x-data="{ openSub3: {{ request()->routeIs('admin.payments') || request()->routeIs('admin.seed-cycle') ? 'true' : 'false' }} }">
                        <button @click="openSub3 = !openSub3"
                                class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-700">
                            Accounting
                            <i :class="{'rotate-90': openSub3}" class="fas fa-chevron-right"></i>
                        </button>

                        <div x-show="openSub3" x-transition class="ml-4 mt-1 space-y-1">
                            <a href="{{ route('admin.payments') }}" @click="open=false"
                               class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.payments') ? 'bg-green-600 font-semibold' : '' }}">
                                Payments
                            </a>

                            <a href="{{ route('admin.seed-cycle') }}" @click="open=false"
                               class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.seed-cycle') ? 'bg-green-600 font-semibold' : '' }}">
                                Seed cycle
                            </a>
                        </div>
                    </div>
                                       

                </nav>

                 <div class="px-4 py-4 border-t">
                <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                <div class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</div>

                <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full text-left text-red-500 hover:underline">
                        Logout
                    </button>
                </form>
            </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="flex flex-col lg:ml-64">
        <livewire:layout.admin-nav />

        <main class="flex-1 p-6 mx-auto w-full">
            {{ $slot }}
        </main>

        <div class="fixed bottom-6 right-6 z-50">
    <div class="relative">
        <!-- FAB button -->
        <button id="fabButton" class="bg-blue-600 text-white p-6 rounded-full shadow-lg hover:bg-blue-800 transition-all transform hover:scale-110 focus:outline-none">
            <i class="fas fa-cogs"></i>
        </button>
        
        <!-- Quick Action Menu -->
        <div id="fabMenu" class="absolute bottom-16 right-0 bg-white shadow-lg rounded-lg w-48 hidden">
            <ul class="space-y-2 p-3">
                <li>
                    <a href="{{ route('admin.review-events') }}"
                       class="text-blue-600 hover:bg-gray-100 w-full text-left py-2 px-4 rounded-md">
                        Alerts
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.review-events') }}"
                       class="text-blue-600 hover:bg-gray-100 w-full text-left py-2 px-4 rounded-md">
                        Payments
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.review-events') }}"
                       class="text-blue-600 hover:bg-gray-100 w-full text-left py-2 px-4 rounded-md">
                        Create Seed Cycle
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.review-events') }}"
                       class="text-blue-600 hover:bg-gray-100 w-full text-left py-2 px-4 rounded-md">
                        Review Events
                    </a>
                </li>
                <li>
                    <button wire:click="enforceLatePayments"
                            class="text-blue-600 hover:bg-gray-100 w-full text-left py-2 px-4 rounded-md">
                        Enforce Late Payments
                    </button>
                </li>
                <li>
                    <a href="{{ route('admin.review-events') }}"
                       class="text-blue-600 hover:bg-gray-100 w-full text-left py-2 px-4 rounded-md">
                        Generate Reports
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    // Toggle FAB menu visibility
    document.getElementById('fabButton').addEventListener('click', function(event) {
        const menu = document.getElementById('fabMenu');
        menu.classList.toggle('hidden');
        event.stopPropagation(); // Prevent event from bubbling up to the document
    });

    // Hide FAB menu when clicking outside of the FAB or menu
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('fabMenu');
        const fabButton = document.getElementById('fabButton');
        
        // Check if the click was outside the FAB button and the menu
        if (!fabButton.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>

    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="bg-gray-100 font-sans min-h-screen">

@auth
    @if(auth()->user()->isAdmin())

    <div class="flex min-h-screen">
{{-- SIDEBAR --}}
<aside class="bg-white border-r w-64 hidden lg:flex flex-col">
    {{-- Logo --}}
    <div class="px-6 py-4 flex items-center justify-between border-b">
        <x-app-logo />
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-6 space-y-2">
        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
            {{-- Icon --}}
            <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 9v-6h4v6m5 0H5a2 2 0 01-2-2V7a2 2 0 012-2h2"/>
            </svg>
            Dashboard
        </a>

        {{-- Configuration --}}
        <a href="{{ route('admin.configuration') }}" class="flex items-center px-3 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('admin.configuration') ? 'bg-gray-200 font-semibold' : '' }}">
            <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Configuration
        </a>

        {{-- Seed Cycles --}}
        <a href="{{ route('admin.seed-cycle') }}" class="flex items-center px-3 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('admin.seed-cycle') ? 'bg-gray-200 font-semibold' : '' }}">
            <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/>
            </svg>
            Seed Cycles
        </a>

        {{-- Beneficiary Requests --}}
        <a href="{{ route('admin.beneficiary.requests') }}" class="flex items-center px-3 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('admin.beneficiary.requests') ? 'bg-gray-200 font-semibold' : '' }}">
            <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Beneficiary Requests
        </a>
    </nav>

    {{-- User Info & Logout --}}
    <div class="px-6 py-4 border-t">
        <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
        <div class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</div>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="text-red-500 text-sm hover:underline w-full text-left">Logout</button>
        </form>
    </div>
</aside>


        {{-- MOBILE SIDEBAR --}}
        <div x-data="{ open: false }" class="lg:hidden">
            <button @click="open = !open" class="p-4 bg-white border-b w-full text-left">Menu</button>
            <nav x-show="open" class="bg-white border-b px-4 py-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.configuration') }}" class="block px-3 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('admin.configuration') ? 'bg-gray-200 font-semibold' : '' }}">
                    Configuration
                </a>
                <a href="{{ route('admin.seed-cycle') }}" class="block px-3 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('admin.seed-cycle') ? 'bg-gray-200 font-semibold' : '' }}">
                    Seed Cycles
                </a>
                <a href="{{ route('admin.beneficiary.requests') }}" class="block px-3 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('admin.beneficiary.requests') ? 'bg-gray-200 font-semibold' : '' }}">
                    Beneficiary Requests
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="text-red-500 text-sm hover:underline w-full text-left">Logout</button>
                </form>
            </nav>
        </div>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 flex flex-col">
            {{-- HEADER --}}
            <header class="bg-white border-b px-6 py-4 flex justify-between items-center">
                <div class="text-xl font-semibold">Admin Panel</div>
                <div class="lg:hidden">
                    <span>{{ auth()->user()->name }}</span>
                </div>
            </header>

            {{-- PAGE CONTENT (centered) --}}
            <main class="flex-1 p-6  mx-auto w-full">
                {{ $slot }}
            </main>
        </div>
    </div>

    @else
        <script>window.location = "{{ route('member.dashboard') }}";</script>
    @endif
@endauth

@guest
<script>window.location = "{{ route('login') }}";</script>
@endguest

</body>
</html>

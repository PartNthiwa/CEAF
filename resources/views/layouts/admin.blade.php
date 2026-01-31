<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <title>Admin Panel</title>
</head>

@php
    abort_unless(auth()->check(), 401);
@endphp

<body class="bg-slate-50 min-h-screen">
<div class="min-h-screen flex flex-col">

    {{-- GLOBAL HEADER --}}
    <header class="sticky top-0 z-50 bg-sky-700 text-white border-b border-sky-800">
        <div class="px-6 py-5 flex items-center justify-between">
            {{-- Logo / Title --}}
            <div class="flex items-center gap-3">
                <x-app-logo class="w-10 h-10" />
                <div class="leading-tight">
                    <div class="font-bold text-lg tracking-tight">CEAF Admin</div>
                    <div class="text-xs text-white/80">Bereavement Registry</div>
                </div>
            </div>

            <div class="flex items-center gap-3">

                {{-- Account dropdown (Desktop) — NO JS --}}
                <details class="relative hidden lg:block group">
                    <summary
                        class="list-none cursor-pointer select-none px-3 py-2 rounded-2xl
                               hover:bg-white/10 transition flex items-center gap-3
                               focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40
                               group-open:bg-white/10 group-open:ring-2 group-open:ring-white/20"
                    >
                        {{-- Avatar --}}
                        <div class="relative shrink-0">
                            <div class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 ring-2 ring-sky-700"></span>
                        </div>

                        {{-- User text --}}
                        <div class="leading-tight text-left">
                            <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-white/80">{{ auth()->user()->email }}</div>
                        </div>

                        {{-- Caret --}}
                        <svg class="w-4 h-4 text-white/80 ml-2 transition-transform duration-200 group-open:rotate-180"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                  clip-rule="evenodd" />
                        </svg>
                    </summary>

                    <div class="absolute right-0 mt-3 w-80 bg-white text-slate-700 border border-gray-200 rounded-2xl shadow-xl overflow-hidden">
                        <div class="px-4 py-3 border-b bg-slate-50">
                            <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</div>
                        </div>

                        <div class="p-2 space-y-1">
                            <a href="{{ route('admin.users') }}"
                               class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-slate-50 transition">
                                <span>Users</span>
                                <span class="text-slate-400">→</span>
                            </a>

                            {{-- Reports dropdown (NO JS) --}}
                            <details class="rounded-xl border border-gray-200 overflow-hidden"
                                     {{ request()->routeIs('admin.reports.*') ? 'open' : '' }}>
                                <summary class="list-none cursor-pointer select-none px-3 py-2 hover:bg-slate-50 transition flex items-center justify-between">
                                    <span class="font-medium">Reports</span>
                                    <span class="text-slate-500">▸</span>
                                </summary>

                                <div class="px-2 py-2 space-y-1 bg-slate-50">
                                    <a href="{{ route('admin.reports.payments') }}"
                                       class="block px-3 py-2 rounded-xl hover:bg-white transition
                                              {{ request()->routeIs('admin.reports.payments*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                        Payments Report
                                    </a>

                                    <a href="{{ route('admin.reports.members') }}"
                                   class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                          {{ request()->routeIs('admin.reports.members*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                    Members Report
                                </a>

                                 <a href="{{ route('admin.reports.events') }}"
                                    class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                            {{ request()->routeIs('admin.reports.events*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                        Events Report
                                    </a>
                                 <a href="{{ route('admin.reports.dependents') }}"
                                   class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                          {{ request()->routeIs('admin.reports.dependents*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                    Dependents Report
                                </a>

                                 <a href="{{ route('admin.reports.beneficiaries') }}"
                                    class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                            {{ request()->routeIs('admin.reports.beneficiaries*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                      Beneficiriries Report
                                    </a>
                                     <a href="{{ route('admin.reports.seed_funds') }}"
                                    class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                            {{ request()->routeIs('admin.reports.seed_funds*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                      Seed Report
                                    </a>
                                </div>
                            </details>

                            <a href="{{ route('admin.configuration') }}"
                               class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-slate-50 transition">
                                <span>Settings</span>
                                <span class="text-slate-400">→</span>
                            </a>
                        </div>

                        <div class="border-t p-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-3 py-2 rounded-xl text-red-600 hover:bg-red-50 font-semibold transition">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </details>

                {{-- Mobile menu (NO JS) --}}
                <details class="lg:hidden relative">
                    <summary class="list-none cursor-pointer select-none px-3 py-2 rounded-2xl hover:bg-white/10 transition">
                        ☰
                    </summary>

                    <div class="absolute right-0 mt-3 w-80 max-w-[92vw] bg-white text-slate-700 border border-gray-200 rounded-2xl shadow-xl overflow-hidden">
                        <div class="px-4 py-3 border-b bg-slate-50">
                            <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</div>
                        </div>

                        <nav class="p-3 space-y-2">
                            <a href="{{ route('admin.dashboard') }}"
                               class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                      {{ request()->routeIs('admin.dashboard') ? 'bg-sky-50 text-sky-700 font-semibold' : '' }}">
                                Dashboard
                            </a>

                            <details class="rounded-xl border border-gray-200 overflow-hidden"
                                     {{ request()->routeIs('admin.members-list','admin.invite-members','admin.approve-members','admin.dependents','admin.beneficiaries') ? 'open' : '' }}>
                                <summary class="list-none cursor-pointer select-none px-3 py-2 hover:bg-slate-50 transition flex items-center justify-between">
                                    <span class="font-medium">Members</span>
                                    <span class="text-slate-500">▸</span>
                                </summary>
                                <div class="px-2 py-2 space-y-1 bg-slate-50">
                                    <a href="{{ route('admin.members-list') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Listing</a>
                                    <a href="{{ route('admin.invite-members') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Invites</a>
                                    <a href="{{ route('admin.approve-members') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Pending Approval</a>
                                    <a href="{{ route('admin.dependents') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Dependents</a>
                                    <a href="{{ route('admin.beneficiaries') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Beneficiaries</a>
                                </div>
                            </details>

                            <details class="rounded-xl border border-gray-200 overflow-hidden"
                                     {{ request()->routeIs('admin.review-events','admin.beneficiary.requests') ? 'open' : '' }}>
                                <summary class="list-none cursor-pointer select-none px-3 py-2 hover:bg-slate-50 transition flex items-center justify-between">
                                    <span class="font-medium">Events</span>
                                    <span class="text-slate-500">▸</span>
                                </summary>
                                <div class="px-2 py-2 space-y-1 bg-slate-50">
                                    <a href="{{ route('admin.beneficiary.requests') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Change requests</a>
                                    <a href="{{ route('admin.review-events') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Review Events</a>
                                </div>
                            </details>

                            <details class="rounded-xl border border-gray-200 overflow-hidden"
                                     {{ request()->routeIs('admin.payments','admin.seed-cycle') ? 'open' : '' }}>
                                <summary class="list-none cursor-pointer select-none px-3 py-2 hover:bg-slate-50 transition flex items-center justify-between">
                                    <span class="font-medium">Accounting</span>
                                    <span class="text-slate-500">▸</span>
                                </summary>
                                <div class="px-2 py-2 space-y-1 bg-slate-50">
                                    <a href="{{ route('admin.payments') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Payments</a>
                                    <a href="{{ route('admin.seed-cycle') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Seed Cycle</a>
                                </div>
                            </details>

                            <details class="rounded-xl border border-gray-200 overflow-hidden"
                                     {{ request()->routeIs('admin.configuration','admin.users','admin.reports.*') ? 'open' : '' }}>
                                <summary class="list-none cursor-pointer select-none px-3 py-2 hover:bg-slate-50 transition flex items-center justify-between">
                                    <span class="font-medium">Settings</span>
                                    <span class="text-slate-500">▸</span>
                                </summary>
                                <div class="px-2 py-2 space-y-1 bg-slate-50">
                                    <a href="{{ route('admin.configuration') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Configuration</a>
                                    <a href="{{ route('admin.users') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Users</a>

                                    {{-- Reports dropdown (NO JS) --}}
                                    <details class="rounded-xl border border-gray-200 overflow-hidden"
                                             {{ request()->routeIs('admin.reports.*') ? 'open' : '' }}>
                                        <summary class="list-none cursor-pointer select-none px-3 py-2 hover:bg-white transition flex items-center justify-between">
                                            <span class="font-medium">Reports</span>
                                            <span class="text-slate-500">▸</span>
                                        </summary>

                                        <div class="px-2 py-2 space-y-1 bg-white">
                                            <a href="{{ route('admin.reports.payments') }}"
                                               class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                                      {{ request()->routeIs('admin.reports.payments*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                                Payments Report
                                            </a>
                                             <a href="{{ route('admin.reports.members') }}"
                                            class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                                    {{ request()->routeIs('admin.reports.members*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                                Members Report
                                            </a>
                                               <a href="{{ route('admin.reports.events') }}"
                                            class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                                    {{ request()->routeIs('admin.reports.events*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                                Events Report
                                            </a>
                                           <a href="{{ route('admin.reports.events') }}"
                                    class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                            {{ request()->routeIs('admin.reports.events*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                        Events Report
                                    </a>
                                 <a href="{{ route('admin.reports.dependents') }}"
                                   class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                          {{ request()->routeIs('admin.reports.dependents*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                    Dependents Report
                                </a>

                                 <a href="{{ route('admin.reports.beneficiaries') }}"
                                    class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                            {{ request()->routeIs('admin.reports.beneficiaries*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                      Beneficiriries Report
                                    </a>
                                    <a href="{{ route('admin.reports.seed_funds') }}"
                                    class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                            {{ request()->routeIs('admin.reports.seed_funds*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                      Seed Report
                                    </a>
                                        </div>
                                    </details>
                                </div>
                            </details>

                            <form method="POST" action="{{ route('admin.logout') }}" class="pt-1">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-3 py-2 rounded-xl text-red-600 hover:bg-red-50 font-semibold transition">
                                    Logout
                                </button>
                            </form>
                        </nav>
                    </div>
                </details>

            </div>
        </div>
    </header>

    {{-- BODY --}}
    <div class="flex flex-1">

        {{-- DESKTOP SIDEBAR (FIXED) --}}
        <aside
            class="hidden lg:block fixed left-0 top-[88px] w-72 bg-white text-slate-700 border-r border-gray-200
                   h-[calc(100vh-88px)] overflow-y-auto"
        >
            <nav class="px-4 py-6 space-y-3">

                <a href="{{ route('admin.dashboard') }}"
                   class="block px-4 py-3 rounded-2xl hover:bg-slate-50 transition
                          {{ request()->routeIs('admin.dashboard') ? 'bg-sky-600 text-white font-semibold shadow-sm' : '' }}">
                    Dashboard
                </a>

                {{-- Members --}}
                <details class="rounded-2xl overflow-hidden border border-gray-200"
                         {{ request()->routeIs('admin.members-list','admin.invite-members','admin.approve-members','admin.dependents','admin.beneficiaries') ? 'open' : '' }}>
                    <summary class="list-none cursor-pointer select-none px-4 py-3 hover:bg-slate-50 transition flex items-center justify-between">
                        <span class="font-semibold">Members</span>
                        <span class="text-slate-500">▸</span>
                    </summary>
                    <div class="px-3 py-3 space-y-2 bg-slate-50">
                        <a href="{{ route('admin.members-list') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Listing</a>
                        <a href="{{ route('admin.invite-members') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Invites</a>
                        <a href="{{ route('admin.approve-members') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Pending Approval</a>
                        <a href="{{ route('admin.dependents') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Dependents</a>
                        <a href="{{ route('admin.beneficiaries') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Beneficiaries</a>
                    </div>
                </details>

                {{-- Events --}}
                <details class="rounded-2xl overflow-hidden border border-gray-200"
                         {{ request()->routeIs('admin.review-events','admin.beneficiary.requests') ? 'open' : '' }}>
                    <summary class="list-none cursor-pointer select-none px-4 py-3 hover:bg-slate-50 transition flex items-center justify-between">
                        <span class="font-semibold">Events</span>
                        <span class="text-slate-500">▸</span>
                    </summary>
                    <div class="px-3 py-3 space-y-2 bg-slate-50">
                        <a href="{{ route('admin.beneficiary.requests') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Change requests</a>
                        <a href="{{ route('admin.review-events') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Review Events</a>
                    </div>
                </details>

                {{-- Accounting --}}
                <details class="rounded-2xl overflow-hidden border border-gray-200"
                         {{ request()->routeIs('admin.payments','admin.seed-cycle') ? 'open' : '' }}>
                    <summary class="list-none cursor-pointer select-none px-4 py-3 hover:bg-slate-50 transition flex items-center justify-between">
                        <span class="font-semibold">Accounting</span>
                        <span class="text-slate-500">▸</span>
                    </summary>
                    <div class="px-3 py-3 space-y-2 bg-slate-50">
                        <a href="{{ route('admin.payments') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Payments</a>
                        <a href="{{ route('admin.seed-cycle') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Seed Cycle</a>
                    </div>
                </details>

                {{-- Settings --}}
                <details class="rounded-2xl overflow-hidden border border-gray-200"
                         {{ request()->routeIs('admin.configuration','admin.users','admin.reports.*') ? 'open' : '' }}>
                    <summary class="list-none cursor-pointer select-none px-4 py-3 hover:bg-slate-50 transition flex items-center justify-between">
                        <span class="font-semibold">Settings</span>
                        <span class="text-slate-500">▸</span>
                    </summary>

                    <div class="px-3 py-3 space-y-2 bg-slate-50">
                        <a href="{{ route('admin.configuration') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Configuration</a>
                        <a href="{{ route('admin.users') }}" class="block px-3 py-2 rounded-xl hover:bg-white transition">Users</a>

                        {{-- Reports dropdown (replaces broken route('admin.reports')) --}}
                        <details class="rounded-xl border border-gray-200 overflow-hidden"
                                 {{ request()->routeIs('admin.reports.*') ? 'open' : '' }}>
                            <summary class="list-none cursor-pointer select-none px-3 py-2 hover:bg-white transition flex items-center justify-between">
                                <span class="font-semibold">Reports</span>
                                <span class="text-slate-500">▸</span>
                            </summary>

                            <div class="px-2 py-2 space-y-1 bg-white">
                                <a href="{{ route('admin.reports.payments') }}"
                                   class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                          {{ request()->routeIs('admin.reports.payments*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                    Payments Report
                                </a>

                                <a href="{{ route('admin.reports.members') }}"
                                   class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                          {{ request()->routeIs('admin.reports.members*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                    Members Report
                                </a>

                                 <a href="{{ route('admin.reports.events') }}"
                                    class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                            {{ request()->routeIs('admin.reports.events*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                        Events Report
                                    </a>
                                 <a href="{{ route('admin.reports.dependents') }}"
                                   class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                          {{ request()->routeIs('admin.reports.dependents*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                    Dependents Report
                                </a>

                                 <a href="{{ route('admin.reports.beneficiaries') }}"
                                    class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                            {{ request()->routeIs('admin.reports.beneficiaries*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                      Beneficiriries Report
                                    </a>
                                     <a href="{{ route('admin.reports.seed_funds') }}"
                                    class="block px-3 py-2 rounded-xl hover:bg-slate-50 transition
                                            {{ request()->routeIs('admin.reports.seed_funds*') ? 'bg-indigo-100 font-semibold' : '' }}">
                                      Seed Report
                                    </a>
                            </div>
                        </details>
                    </div>
                </details>

                {{-- Quick Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="pt-1">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-3 rounded-2xl text-red-600 hover:bg-red-50 font-semibold transition border border-gray-200">
                        Logout
                    </button>
                </form>

            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-6 lg:ml-72 bg-gray-300">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>

    </div>
</div>

    @livewireScripts
</body>
</html>

<div x-data="{ open: false }" class="min-h-screen">

    {{-- Desktop Sidebar (LG+) --}}
    <aside class="hidden lg:flex fixed inset-y-0 left-0 w-64 bg-gray-800 text-gray-200 border-r flex-col">
        <div class="px-6 py-4 flex items-center justify-between border-b bg-white">
            <x-app-logo />
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-green-600 font-semibold' : '' }}">
                Dashboard
            </a>

            {{-- Members --}}
            <div x-data="{ open: {{ request()->routeIs('admin.dependents') || request()->routeIs('admin.beneficiaries') || request()->routeIs('admin.beneficiary.requests') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dependents') || request()->routeIs('admin.beneficiaries') || request()->routeIs('admin.beneficiary.requests') ? 'bg-green-600 font-semibold' : '' }}">
                    Members
                    <svg :class="{'rotate-90': open}" class="h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">
                    <a href="{{ route('admin.dependents') }}"
                       class="block px-3 py-2 text-sm rounded hover:bg-gray-600 {{ request()->routeIs('admin.dependents') ? 'bg-green-700 font-semibold' : '' }}">
                        Dependents
                    </a>

                    <div x-data="{ open: {{ request()->routeIs('admin.beneficiaries') || request()->routeIs('admin.beneficiary.requests') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-600 {{ request()->routeIs('admin.beneficiaries') || request()->routeIs('admin.beneficiary.requests') ? 'bg-green-700 font-semibold' : '' }}">
                            Beneficiaries
                            <svg :class="{'rotate-90': open}" class="h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">
                            <a href="{{ route('admin.beneficiaries') }}"
                               class="block px-3 py-2 text-sm rounded hover:bg-gray-600 {{ request()->routeIs('admin.beneficiaries') ? 'bg-green-700 font-semibold' : '' }}">
                                All Beneficiaries
                            </a>

                            <a href="{{ route('admin.beneficiary.requests') }}"
                               class="block px-3 py-2 text-sm rounded hover:bg-gray-600 {{ request()->routeIs('admin.beneficiary.requests') ? 'bg-green-700 font-semibold' : '' }}">
                                Change Requests
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payments --}}
            <a href="{{ route('admin.payments') }}"
               class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.payments') ? 'bg-green-600 font-semibold' : '' }}">
                Payments
            </a>

            <a href="{{ route('admin.seed-cycle') }}"
               class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.seed-cycle') ? 'bg-green-600 font-semibold' : '' }}">
                Seed Cycles
            </a>

            <a href="{{ route('admin.configuration') }}"
               class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.configuration') ? 'bg-green-600 font-semibold' : '' }}">
                Configuration
            </a>
        </nav>

        <div class="px-6 py-4 border-t">
            <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
            <div class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="text-red-500 text-sm hover:underline w-full text-left">Logout</button>
            </form>
        </div>
    </aside>

    {{-- =========================
         MOBILE MENU (SMALL)
    ========================== --}}
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

            <div class="relative w-72 bg-gray-800 text-gray-200 min-h-screen">
                <div class="flex items-center justify-between px-4 py-4 border-b">
                    <div class="font-bold">Menu</div>
                    <button @click="open = false" class="text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <nav class="px-4 py-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" @click="open=false" class="block px-3 py-2 rounded hover:bg-gray-700">
                        Dashboard
                    </a>

                    <a href="{{ route('admin.dependents') }}" @click="open=false" class="block px-3 py-2 rounded hover:bg-gray-700">
                        Dependents
                    </a>

                    <a href="{{ route('admin.beneficiaries') }}" @click="open=false" class="block px-3 py-2 rounded hover:bg-gray-700">
                        Beneficiaries
                    </a>

                    <a href="{{ route('admin.beneficiary.requests') }}" @click="open=false" class="block px-3 py-2 rounded hover:bg-gray-700">
                        Change Requests
                    </a>

                    <a href="{{ route('admin.payments') }}" @click="open=false" class="block px-3 py-2 rounded hover:bg-gray-700">
                        Payments
                    </a>

                    <a href="{{ route('admin.seed-cycle') }}" @click="open=false" class="block px-3 py-2 rounded hover:bg-gray-700">
                        Seed Cycles
                    </a>

                    <a href="{{ route('admin.configuration') }}" @click="open=false" class="block px-3 py-2 rounded hover:bg-gray-700">
                        Configuration
                    </a>
                </nav>

                <div class="px-4 py-4 border-t">
                    <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</div>

                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full text-left text-red-500 hover:underline">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<nav x-data="{ open: false }"
     x-on:keydown.escape.window="open = false"
     class="bg-blue-900 border-b border-blue-950/40"
>
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between gap-3">

            {{-- LEFT: Brand + Links --}}
            <div class="flex items-center gap-6 min-w-0">
                {{-- Brand --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="shrink-0 flex items-center gap-3 text-white font-bold text-lg sm:text-xl">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/15">
                        <span class="text-white">A</span>
                    </span>
                    <span class="truncate">Admin Panel</span>
                </a>

                {{-- Desktop links --}}
                <div class="hidden sm:flex items-center gap-6">
                    @php
                        $navLink = function (string $route) {
                            $active = request()->routeIs($route);
                            return $active
                                ? 'text-white border-white/80'
                                : 'text-white/80 border-transparent hover:text-white hover:border-white/40';
                        };
                    @endphp

                    <a href="{{ route('admin.manage-ceaf-users') }}"
                       class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-semibold transition {{ $navLink('admin.manage-ceaf-users') }}">
                        Users
                    </a>

                    <a href="{{ route('admin.reports') }}"
                       class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-semibold transition {{ $navLink('admin.reports') }}">
                        Reports
                    </a>

                    <a href="{{ route('admin.members-list') }}"
                       class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-semibold transition {{ $navLink('admin.members-list') }}">
                        Members
                    </a>
                </div>
            </div>

            {{-- CENTER (optional): Search slot --}}
            <div class="hidden lg:flex flex-1 justify-center px-4">
                {{-- If you want a search field, uncomment this --}}
                {{-- 
                <div class="w-full max-w-md">
                    <input type="text"
                           placeholder="Search…"
                           class="w-full rounded-lg bg-white/10 px-3 py-2 text-sm text-white placeholder:text-white/60
                                  ring-1 ring-white/15 focus:outline-none focus:ring-2 focus:ring-white/30">
                </div>
                --}}
            </div>

            {{-- RIGHT: Quick actions + user menu --}}
            <div class="flex items-center gap-2">

                {{-- Quick nav buttons (desktop) --}}
                <div class="hidden md:flex items-center gap-2">
                    <button type="button"
                            onclick="window.history.back()"
                            class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-sm font-semibold text-white
                                   ring-1 ring-white/15 hover:bg-white/15 transition">
                        ← Back
                    </button>

                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-blue-900
                              hover:bg-blue-50 transition">
                        Dashboard
                    </a>
                </div>

                {{-- User dropdown (desktop) --}}
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button type="button"
                                    class="inline-flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-700
                                           hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-white/40 transition">
                                {{-- Avatar initial --}}
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-900 font-bold">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </span>

                                <div class="hidden lg:block text-left">
                                    <div class="text-sm font-semibold leading-4">
                                        {{ auth()->user()->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 leading-4">
                                        {{ auth()->user()->email }}
                                    </div>
                                </div>

                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3">
                                <div class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</div>
                            </div>

                            <div class="border-t px-4 py-3">
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left text-sm font-semibold text-red-600 hover:underline">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

                {{-- Hamburger (mobile) --}}
                <div class="sm:hidden">
                    <button @click="open = !open"
                            type="button"
                            class="inline-flex items-center justify-center rounded-lg p-2 text-white/80
                                   hover:text-white hover:bg-white/10 ring-1 ring-white/15 transition"
                            aria-label="Open menu"
                            :aria-expanded="open.toString()">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }"
                                  class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }"
                                  class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-cloak x-show="open" x-transition
         class="sm:hidden border-t border-white/10 bg-blue-950/40">
        <div class="px-4 py-3 space-y-2 text-white">
            <a href="{{ route('admin.dashboard') }}"
               class="block rounded-lg px-3 py-2 text-sm font-semibold hover:bg-white/10">
                Dashboard
            </a>

            <a href="{{ route('admin.members-list') }}"
               class="block rounded-lg px-3 py-2 text-sm font-semibold hover:bg-white/10">
                Members
            </a>

            <a href="{{ route('admin.manage-ceaf-users') }}"
               class="block rounded-lg px-3 py-2 text-sm font-semibold hover:bg-white/10">
                Users
            </a>

            <a href="{{ route('admin.reports') }}"
               class="block rounded-lg px-3 py-2 text-sm font-semibold hover:bg-white/10">
                Reports
            </a>

            <button type="button"
                    onclick="window.history.back()"
                    class="w-full text-left rounded-lg px-3 py-2 text-sm font-semibold hover:bg-white/10">
                ← Back
            </button>
        </div>

        <div class="border-t border-white/10 px-4 py-4 text-white">
            <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
            <div class="text-xs text-white/70 truncate">{{ auth()->user()->email }}</div>

            <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
                @csrf
                <button type="submit"
                        class="w-full text-left rounded-lg px-3 py-2 text-sm font-semibold text-red-200 hover:bg-white/10">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

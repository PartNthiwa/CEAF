
<nav x-data="{ open: false }" class="bg-blue-900 border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class=" mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
            <div class="shrink-0 flex items-center text-white font-bold text-xl">
                   <h1>Admin Panel </h1>
                   
                </div>

                <!-- Navigation Links -->
                 <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Users Link -->
                    @php
                        $usersClasses = request()->routeIs('admin.manage-ceaf-users')
                            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-white focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
                            : 'inline-flex items-center px-1 pt-1 border-b-4 border-transparent text-sm font-medium leading-5 text-white hover:border-green-200 focus:outline-none  focus:bordegreen-900 transition duration-150 ease-in-out';
                    @endphp
                    <a 
                        href="{{ route('admin.manage-ceaf-users') }}" 
                        class="{{ $usersClasses }}">
                        Users
                    </a>

                    <!-- Reports Link -->
                    @php
                        $reportsClasses = request()->routeIs('admin.reports')
                            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-white focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
                            : 'inline-flex items-center px-1 pt-1 border-b-4 border-transparent text-sm font-medium leading-10 text-white hover:border-green-200 focus:outline-none  focus:border-red-900 transition duration-150 ease-in-out';
                    @endphp
                    <a 
                        href="{{ route('admin.reports') }}" 
                        class="{{ $reportsClasses }}">
                        Reports
                    </a>
                </div>


            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                         <!-- Authentication -->
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
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden text-white sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-red-600">
                 <div class="mt-3 space-y-1 bg-blue-400">
                <!-- Authentication -->
                <div class="px-4 py-4 border-t ">
                <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                <div class="text-xs text-gray-900 truncate">{{ auth()->user()->email }}</div>

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
</nav>

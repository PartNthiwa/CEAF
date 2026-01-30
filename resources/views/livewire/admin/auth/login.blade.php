<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg">

        <!-- CEAF Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/ceaflogo.png') }}" alt="CEAF Logo" class="w-28 h-28 object-contain">
        </div>

        <h2 class="text-2xl font-bold text-center mb-4">Admin Login</h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit.prevent="login" class="space-y-5">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input wire:model="email" type="email"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="admin@ceaf.org" required autofocus>
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input wire:model="password" type="password"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="..........." required>
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

           <button type="submit"
                wire:loading.attr="disabled"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg px-4 py-2 transition flex items-center justify-center gap-2">

                <span wire:loading.remove>Login to Control Panel</span>
               

                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"></path>
                    </svg>
                    Logging in...
                </span>
            </button>
            <p class='text-sm text-center hover:text-red-600 shadow-sm'><small>This takes you to the administrator Dashboard</small> <br> <small> &copy; {{ date('Y') }} {{ config('app.name', 'My App') }}. All rights reserved.</small></p>
        </form>
    </div>
</div>

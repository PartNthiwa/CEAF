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
                       placeholder="admin@ceaf.com" required autofocus>
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input wire:model="password" type="password"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="••••••••" required>
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg px-4 py-2 transition">
                Login
            </button>
        </form>
    </div>
</div>

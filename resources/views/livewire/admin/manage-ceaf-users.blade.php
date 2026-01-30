<div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6">

    {{-- Header + Navigation --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div>
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">CEAF Users</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">
                Manage CEAF Users
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Create internal users and manage roles & access.
            </p>
        </div>

        <div class="flex gap-2 w-full sm:w-auto">
            <button
                type="button"
                onclick="window.history.back()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                       bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold
                       hover:bg-gray-200 transition"
            >
                ← Back
            </button>

            <a
                href="{{ route('admin.dashboard') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                       bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-lg font-semibold
                       hover:bg-gray-50 transition"
            >
                Dashboard
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session()->has('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Create / Update User Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">
                {{ $updateMode ? 'Update User' : 'Create CEAF User' }}
            </h3>
            <p class="text-sm text-gray-600">
                Fill in the user details below.
            </p>
        </div>

        <form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" class="p-4 sm:p-6 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input
                        type="text"
                        wire:model.defer="name"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Full name"
                    >
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        wire:model.defer="email"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="user@example.com"
                    >
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <select
                        wire:model.defer="role"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Password
                        @if($updateMode)
                            <span class="text-xs text-gray-500 font-normal">(leave blank if not changing)</span>
                        @endif
                    </label>

                    <div class="mt-1 relative">
                        <input
                            type="{{ $passwordVisible ? 'text' : 'password' }}"
                            wire:model.defer="password"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pr-12"
                            placeholder="Enter password"
                        >

                        <button
                            type="button"
                            wire:click="togglePasswordVisibility"
                            class="absolute inset-y-0 right-2 inline-flex items-center px-2 text-gray-500 hover:text-gray-700"
                            aria-label="Toggle password visibility"
                        >
                            {{ $passwordVisible ? '🙈' : '👁️' }}
                        </button>
                    </div>

                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Confirm Password --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>

                    <div class="mt-1 relative">
                        <input
                            type="{{ $confirmPasswordVisible ? 'text' : 'password' }}"
                            wire:model.defer="confirmPassword"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pr-12"
                            placeholder="Confirm password"
                        >

                        <button
                            type="button"
                            wire:click="toggleConfirmPasswordVisibility"
                            class="absolute inset-y-0 right-2 inline-flex items-center px-2 text-gray-500 hover:text-gray-700"
                            aria-label="Toggle confirm password visibility"
                        >
                            {{ $confirmPasswordVisible ? '🙈' : '👁️' }}
                        </button>
                    </div>

                    @error('confirmPassword')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Help text / empty column --}}
                <div class="hidden md:block text-sm text-gray-500 self-end">
                    Tip: Assign roles carefully — they control access in admin.
                </div>
            </div>

            {{-- Actions --}}
            <div class="pt-4 border-t flex flex-col sm:flex-row gap-3 sm:justify-end">
                @if($updateMode)
                    <button
                        type="button"
                        wire:click="$set('updateMode', false)"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
                    >
                        Cancel Update
                    </button>
                @endif

                <button
                    type="submit"
                    class="w-full sm:w-auto px-5 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition"
                >
                    {{ $updateMode ? 'Update User' : 'Create User' }}
                </button>
            </div>

            <div wire:loading class="text-sm text-gray-500">
                Saving...
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="p-4 sm:p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Users</h3>
                <p class="text-sm text-gray-600">All internal CEAF accounts.</p>
            </div>
            <p class="text-sm text-gray-400">{{ method_exists($users, 'total') ? $users->total() : '' }}</p>
        </div>

        {{-- Desktop --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Name</th>
                        <th class="px-6 py-3 text-left font-medium">Email</th>
                        <th class="px-6 py-3 text-left font-medium">Role</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($users as $user)
                        @php
                            $role = strtolower($user->role ?? 'user');
                            $rolePill = match($role) {
                                'admin' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
                                'manager' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                'staff' => 'bg-green-50 text-green-700 ring-green-600/20',
                                default => 'bg-gray-50 text-gray-700 ring-gray-500/20'
                            };
                        @endphp

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 font-medium text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-3 text-gray-700">
                                <span class="break-all">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-3">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $rolePill }}">
                                    {{ ucfirst($role) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex gap-2">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $user->id }})"
                                        class="inline-flex items-center justify-center px-3 py-2 rounded-lg
                                               bg-blue-600 text-white hover:bg-blue-700 transition text-xs font-semibold"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="delete({{ $user->id }})"
                                        class="inline-flex items-center justify-center px-3 py-2 rounded-lg
                                               bg-red-600 text-white hover:bg-red-700 transition text-xs font-semibold"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="md:hidden divide-y">
            @forelse($users as $user)
                @php
                    $role = strtolower($user->role ?? 'user');
                    $rolePill = match($role) {
                        'admin' => 'bg-indigo-50 text-indigo-700',
                        'manager' => 'bg-blue-50 text-blue-700',
                        'staff' => 'bg-green-50 text-green-700',
                        default => 'bg-gray-50 text-gray-700'
                    };
                @endphp

                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 break-words">{{ $user->name }}</p>
                            <p class="text-sm text-gray-600 break-all">{{ $user->email }}</p>
                        </div>

                        <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold {{ $rolePill }}">
                            {{ ucfirst($role) }}
                        </span>
                    </div>

                    <div class="flex gap-2">
                        <button
                            type="button"
                            wire:click="edit({{ $user->id }})"
                            class="w-full px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-sm font-semibold"
                        >
                            Edit
                        </button>

                        <button
                            type="button"
                            wire:click="delete({{ $user->id }})"
                            class="w-full px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition text-sm font-semibold"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-gray-500">
                    No users found.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="p-4 sm:p-6 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>

</div>

<div class="p-6 bg-white rounded shadow space-y-4">
    <h2 class="text-2xl font-bold mb-4">Create Ceaf User</h2>

    @if(session()->has('success'))
        <div class="p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" class="space-y-4">
        <!-- Form Fields for Creating/Updating Ceaf User -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Name Field -->
            <div>
                <label class="block font-medium">Name</label>
                <input type="text" wire:model="name" class="border rounded px-3 py-2 w-full" />
                @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Email Field -->
            <div>
                <label class="block font-medium">Email</label>
                <input type="email" wire:model="email" class="border rounded px-3 py-2 w-full" />
                @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Role Field (Dropdown) -->
            <div>
                <label class="block font-medium">Role</label>
                <select wire:model="role" class="border rounded px-3 py-2 w-full">
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
                @error('role') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label class="block font-medium">Password</label>
                <div class="relative">
                    <input type="{{ $passwordVisible ? 'text' : 'password' }}" wire:model="password" class="border rounded px-3 py-2 w-full" />
                 <span class="absolute right-3 top-3 cursor-pointer" wire:click="togglePasswordVisibility">
                    <i class="fa fa-eye" :class="{'fa-eye-slash': passwordVisible}"></i>
                </span>

                </div>
                @error('password') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Confirm Password Field -->
            <div>
                <label class="block font-medium">Confirm Password</label>
                <div class="relative">
                    <input type="{{ $confirmPasswordVisible ? 'text' : 'password' }}" wire:model="confirmPassword" class="border rounded px-3 py-2 w-full" />
                    <span class="absolute right-3 top-3 cursor-pointer" wire:click="toggleConfirmPasswordVisibility">
                        <i class="fa fa-eye" :class="{'fa-eye-slash': $confirmPasswordVisible}"></i>
                    </span>
                </div>
                @error('confirmPassword') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                {{ $updateMode ? 'Update User' : 'Create User' }}
            </button>
        </div>
    </form>

    <!-- Table to display Ceaf Users -->
    <div class="mt-6">
        <h3 class="text-xl font-bold mb-4">Manage Ceaf Users</h3>
        <table class="min-w-full border-collapse table-auto">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b text-left">Name</th>
                    <th class="px-4 py-2 border-b text-left">Email</th>
                    <th class="px-4 py-2 border-b text-left">Role</th>
                    <th class="px-4 py-2 border-b text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ ucfirst($user->role) }}</td>
                        <td class="px-4 py-2">
                            <button wire:click="edit({{ $user->id }})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                Edit
                            </button>
                            <button wire:click="delete({{ $user->id }})" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 ml-2">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

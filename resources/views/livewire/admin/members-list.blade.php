<div x-data="{ showForm: false, showDeleteModal: false, deleteId: null }"
     x-on:open-modal.window="showForm = true"
     x-on:close-modal.window="showForm = false">

    <div class="p-6 bg-white rounded shadow">

        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-bold">Members Management</h2>
                <p class="text-gray-600">Manage all members and their details here.</p>
            </div>

            <button @click="showForm = true"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add Member
            </button>
        </div>

        @if (session()->has('success'))
            <div class="mb-4 rounded bg-green-50 border border-green-200 p-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Filters -->
        <div class="flex flex-wrap gap-4 mb-4">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search by member name"
                   class="border rounded px-3 py-2 w-full sm:w-64">

            <select wire:model="status" class="border rounded px-3 py-2">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto mt-4">
            <table class="min-w-full bg-white border border-gray-200 rounded">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">#</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Email</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Role</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($members as $index => $member)
                        <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="px-4 py-2 text-sm">{{ $members->firstItem() + $index }}</td>
                            <td class="px-4 py-2 text-sm">{{ $member->user->name }}</td>
                            <td class="px-4 py-2 text-sm">{{ $member->user->email }}</td>

                            <td class="px-4 py-2 text-sm">
                                <span class="{{ $member->membership_status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ucfirst($member->membership_status) }}
                                </span>
                            </td>

                            <td class="px-4 py-2 text-sm">
                                {{ ucfirst($member->role ?? 'N/A') }}
                            </td>

                            <td class="px-4 py-2 text-sm space-x-1 flex items-center">
                                <button wire:click="edit({{ $member->id }})"
                                        class="bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600">
                                    Edit
                                </button>

                                <button @click="showDeleteModal = true; deleteId = {{ $member->id }}"
                                        class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">
                                No members found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $members->links() }}
        </div>

    </div>

    <!-- Create / Edit Modal -->
    <div x-show="showForm" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow max-w-lg w-full">
            <h3 class="text-lg font-semibold mb-4">
                {{ $isEdit ? 'Edit Member' : 'Add Member' }}
            </h3>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="text-sm font-medium">Name</label>
                    <input wire:model="name" type="text" class="border rounded w-full px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input wire:model="email" type="email" class="border rounded w-full px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-medium">Role</label>
                    <input wire:model="role" type="text" class="border rounded w-full px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-medium">Status</label>
                    <select wire:model="membership_status" class="border rounded w-full px-3 py-2">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button @click="showForm = false" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                    Cancel
                </button>
                <button wire:click="save" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                    {{ $isEdit ? 'Update' : 'Create' }}
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow max-w-sm w-full">
            <h3 class="text-lg font-semibold mb-4">
                Confirm Delete
            </h3>
            <p class="mb-4 text-gray-600">Are you sure you want to delete this member?</p>

            <div class="flex justify-end space-x-2">
                <button @click="showDeleteModal = false" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                    Cancel
                </button>
                <button wire:click="delete(deleteId)" @click="showDeleteModal = false"
                        class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

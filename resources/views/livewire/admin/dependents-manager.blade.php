
<div x-data="{ showDeleteModal: false, deleteId: null }" class="p-6 bg-white rounded shadow">

    <h2 class="text-xl font-bold mb-4">Dependents Management</h2>
    <p class="text-gray-600 mb-4">Manage dependents linked to each member here.</p>


    @if ($errors->any())
        <div class="mb-4 rounded bg-red-50 border border-red-200 p-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif
    <!-- Filters -->
    <div class="flex flex-wrap gap-4 mb-4">
        <input type="text" wire:model.debounce.300ms="search" placeholder="Search by member name" class="border rounded px-3 py-2 w-full sm:w-64">
        <select wire:model="relationship" class="border rounded px-3 py-2">
            <option value="">All Relationships</option>
            <option value="spouse">Spouse</option>
            <option value="child">Child</option>
            <option value="parent">Parent</option>
            <option value="other">Other</option>
        </select>
        <select wire:model="status" class="border rounded px-3 py-2">
            <option value="">All Statuses</option>
            <option value="active">Active</option>
            <option value="deceased">Deceased</option>
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto mt-4">
        <table class="min-w-full bg-white border border-gray-200 rounded">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">#</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Member</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Dependent Name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Relationship</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Profile Complete</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dependents as $index => $dependent)
                <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                    <td class="px-4 py-2 text-sm">{{ $dependents->firstItem() + $index }}</td>
                    <td class="px-4 py-2 text-sm">{{ $dependent->member->user->name ?? 'N/A' }}</td>

                    <!-- Inline Edit -->
                    <td class="px-4 py-2 text-sm">
                        @if($editDependentId === $dependent->id)
                            <input type="text" wire:model.defer="editName" class="border rounded px-2 py-1 w-full">
                        @else
                            {{ $dependent->name }}
                        @endif
                    </td>

                    <td class="px-4 py-2 text-sm">
                        @if($editDependentId === $dependent->id)
                            <select wire:model.defer="editRelationship" class="border rounded px-2 py-1 w-full">
                                <option value="spouse">Spouse</option>
                                <option value="child">Child</option>
                                <option value="parent">Parent</option>
                                <option value="other">Other</option>
                            </select>
                        @else
                            {{ ucfirst($dependent->relationship) }}
                        @endif
                    </td>

                    <td class="px-4 py-2 text-sm">
                        @if($editDependentId === $dependent->id)
                            <select wire:model.defer="editStatus" class="border rounded px-2 py-1 w-full">
                                <option value="active">Active</option>
                                <option value="deceased">Deceased</option>
                            </select>
                        @else
                            <span class="{{ $dependent->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                                {{ ucfirst($dependent->status) }}
                            </span>
                        @endif
                    </td>

                    <td class="px-4 py-2 text-sm">
                        @if($editDependentId === $dependent->id)
                            <input type="checkbox" wire:model.defer="editProfileComplete">
                        @else
                            <span class="{{ $dependent->profile_completed ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $dependent->profile_completed ? 'Yes' : 'No' }}
                            </span>
                        @endif
                    </td>

                    <!-- Actions -->
                    <td class="px-4 py-2 text-sm space-x-1 flex items-center">
                        @if($editDependentId === $dependent->id)
                            <!-- Save Icon -->
                            <button wire:click="save" class="bg-green-600 text-white p-2 rounded hover:bg-green-700" title="Save">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <!-- Cancel Icon -->
                            <button wire:click="$set('editDependentId', null)" class="bg-gray-300 p-2 rounded hover:bg-gray-400" title="Cancel">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @else
                            <!-- Edit Icon -->
                            <button wire:click="edit({{ $dependent->id }})" class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-6-6l6 6m-6-6l-6 6" />
                                </svg>
                            </button>

                            <!-- Toggle Complete Icon -->
                            <button wire:click="toggleComplete({{ $dependent->id }})" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600" title="Toggle Complete">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m0 0a9 9 0 11-6-6 9 9 0 016 6z" />
                                </svg>
                            </button>

                            <!-- Delete Icon -->
                            <button @click="showDeleteModal = true; deleteId = {{ $dependent->id }}" class="bg-red-600 text-white p-2 rounded hover:bg-red-700" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-2 text-center text-gray-500">No dependents found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $dependents->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow max-w-sm w-full">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M6.938 18h10.124C19.201 18 20 17.201 20 16.062V7.938C20 6.799 19.201 6 18.062 6H6.938C5.799 6 5 6.799 5 7.938v8.124C5 17.201 5.799 18 6.938 18z" />
                </svg>
                Confirm Delete
            </h3>
            <p class="mb-4 text-gray-600">Are you sure you want to delete this dependent? This action cannot be undone.</p>

            <div class="flex justify-end space-x-2">
                <button @click="showDeleteModal = false" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
                <button wire:click="delete(deleteId)" @click="showDeleteModal = false" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
            </div>
        </div>
    </div>

</div>

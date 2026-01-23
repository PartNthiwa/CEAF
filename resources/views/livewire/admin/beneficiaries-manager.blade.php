<div x-data="{ showDeleteModal: false, deleteId: null }" class="p-6 bg-white rounded shadow">

    <h2 class="text-xl font-bold mb-4">Beneficiaries Management</h2>
    <p class="text-gray-600 mb-4">Manage beneficiaries linked to each member here.</p>

    @if($percentageErrorData)
        <div class="mb-4 rounded border border-red-300 bg-red-50 p-4 text-sm">
            <h4 class="font-semibold text-red-700 mb-2">
                Allocation exceeds 100% for {{ $percentageErrorData['member'] }}
            </h4>

            <ul class="mb-2 text-gray-700 space-y-1">
                @foreach($percentageErrorData['beneficiaries'] as $b)
                    <li class="flex justify-between">
                        <span>{{ $b['name'] }}</span>
                        <span class="font-semibold">{{ $b['percentage'] }}%</span>
                    </li>
                @endforeach
            </ul>

            <div class="border-t pt-2 mt-2 text-gray-700">
                <p>
                    <strong>Current total:</strong>
                    {{ $percentageErrorData['current_total'] }}%
                </p>
                <p>
                    <strong>Remaining:</strong>
                    {{ $percentageErrorData['remaining'] }}%
                </p>
                <p class="text-red-700 font-semibold mt-1">
                    You attempted to allocate {{ $percentageErrorData['attempted'] }}%.
                </p>
            </div>
        </div>
    @endif


    <!-- Filters -->
    <div class="flex flex-wrap gap-4 mb-4">
        <input
            type="text"
            wire:model.debounce.300ms="search"
            placeholder="Search by member or beneficiary name"
            class="border rounded px-3 py-2 w-full sm:w-64"
        >
    </div>

    <!-- Table -->
    <div class="overflow-x-auto mt-4">
        <table class="min-w-full bg-white border border-gray-200 rounded">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">#</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Member</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Beneficiary Name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Contact</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Percentage</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Actions</th>
                </tr>
            </thead>

            <tbody>
            @forelse($beneficiaries as $index => $beneficiary)
                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                    <td class="px-4 py-2 text-sm">
                        {{ $beneficiaries->firstItem() + $index }}
                    </td>

                    <td class="px-4 py-2 text-sm">
                        {{ $beneficiary->member->user->name ?? 'N/A' }}
                    </td>

                    <!-- Name -->
                    <td class="px-4 py-2 text-sm">
                        @if($editId === $beneficiary->id)
                            <input
                                type="text"
                                wire:model.defer="editName"
                                class="border rounded px-2 py-1 w-full"
                            >
                        @else
                            {{ $beneficiary->name }}
                        @endif
                    </td>

                    <!-- Contact -->
                    <td class="px-4 py-2 text-sm">
                        @if($editId === $beneficiary->id)
                            <input
                                type="text"
                                wire:model.defer="editContact"
                                class="border rounded px-2 py-1 w-full"
                            >
                        @else
                            {{ $beneficiary->contact ?? '-' }}
                        @endif
                    </td>

                    <!-- Percentage -->
                    <td class="px-4 py-2 text-sm">
                        @if($editId === $beneficiary->id)
                            <input
                                type="number"
                                min="1"
                                max="100"
                                wire:model.defer="editPercentage"
                                class="border rounded px-2 py-1 w-20"
                            >
                        @else
                            <span class="font-semibold">
                                {{ $beneficiary->percentage }}%
                            </span>
                        @endif
                    </td>

                    <!-- Actions -->
                    <td class="px-4 py-2 text-sm space-x-1 flex items-center">
                        @if($editId === $beneficiary->id)
                            <!-- Save -->
                            <button wire:click="save"
                                    class="bg-green-600 text-white p-2 rounded hover:bg-green-700"
                                    title="Save">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>

                            <!-- Cancel -->
                            <button wire:click="resetEdit"
                                    class="bg-gray-300 p-2 rounded hover:bg-gray-400"
                                    title="Cancel">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        @else
                            <!-- Edit -->
                            <button wire:click="edit({{ $beneficiary->id }})"
                                    class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700"
                                    title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-6-6l6 6m-6-6l-6 6"/>
                                </svg>
                            </button>

                            <!-- Delete -->
                            <button
                                @click="showDeleteModal = true; deleteId = {{ $beneficiary->id }}"
                                class="bg-red-600 text-white p-2 rounded hover:bg-red-700"
                                title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6"
                        class="px-4 py-3 text-center text-gray-500">
                        No beneficiaries found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $beneficiaries->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal"
         x-transition
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow max-w-sm w-full">
            <h3 class="text-lg font-semibold mb-4 text-red-600">
                Confirm Delete
            </h3>

            <p class="mb-4 text-gray-600">
                Are you sure you want to delete this beneficiary?
                This action cannot be undone.
            </p>

            <div class="flex justify-end space-x-2">
                <button @click="showDeleteModal = false"
                        class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                    Cancel
                </button>
                <button
                    wire:click="delete(deleteId)"
                    @click="showDeleteModal = false"
                    class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">
                    Delete
                </button>
            </div>
        </div>
    </div>

</div>

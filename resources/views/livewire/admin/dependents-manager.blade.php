<div
    x-data="{ showDeleteModal: false, deleteId: null }"
    x-on:keydown.escape.window="showDeleteModal = false"
    class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6"
>
    {{-- Header + Navigation --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Dependents</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Dependents Management</h2>
            <p class="text-sm text-gray-600 mt-1">Manage dependents linked to each member here.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2">
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

            <a
                href="{{ route('admin.members-list') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                       bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-lg font-semibold
                       hover:bg-gray-50 transition"
            >
                Members
            </a>
        </div>
    </div>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
            <p class="text-sm text-gray-600 mt-1">Search and narrow down dependents.</p>
        </div>

        <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="text-sm font-medium text-gray-700">Search</label>
                <input
                    type="text"
                    wire:model.debounce.300ms="search"
                    placeholder="Search by member name..."
                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Relationship</label>
                <select
                    wire:model="relationship"
                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">All Relationships</option>
                    <option value="spouse">Spouse</option>
                    <option value="child">Child</option>
                    <option value="parent">Parent</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Status</label>
                <select
                    wire:model="status"
                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="deceased">Deceased</option>
                </select>
            </div>

            {{-- Loading indicator --}}
            <div class="md:col-span-3">
                <div wire:loading class="text-xs text-gray-500">
                    Loading...
                </div>
            </div>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">#</th>
                        <th class="px-6 py-3 text-left font-medium">Member</th>
                        <th class="px-6 py-3 text-left font-medium">Dependent</th>
                        <th class="px-6 py-3 text-left font-medium">Relationship</th>
                        <th class="px-6 py-3 text-left font-medium">Status</th>
                        <th class="px-6 py-3 text-left font-medium">Profile</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($dependents as $index => $dependent)
                        @php
                            $rowNo = $dependents->firstItem() + $index;

                            $rel = strtolower($dependent->relationship ?? 'other');
                            $relPill = match($rel) {
                                'spouse' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
                                'child' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                'parent' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                default => 'bg-gray-100 text-gray-700 ring-gray-500/20',
                            };

                            $st = strtolower($dependent->status ?? 'active');
                            $statusPill = $st === 'active'
                                ? 'bg-green-50 text-green-700 ring-green-600/20'
                                : 'bg-red-50 text-red-700 ring-red-600/20';

                            $profilePill = $dependent->profile_completed
                                ? 'bg-green-50 text-green-700 ring-green-600/20'
                                : 'bg-amber-50 text-amber-700 ring-amber-600/20';
                        @endphp

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 text-gray-600">{{ $rowNo }}</td>

                            <td class="px-6 py-3 font-medium text-gray-900">
                                {{ $dependent->member->user->name ?? 'N/A' }}
                            </td>

                            {{-- Dependent Name --}}
                            <td class="px-6 py-3">
                                @if($editDependentId === $dependent->id)
                                    <input type="text" wire:model.defer="editName"
                                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                @else
                                    <span class="font-medium text-gray-900">{{ $dependent->name }}</span>
                                @endif
                            </td>

                            {{-- Relationship --}}
                            <td class="px-6 py-3">
                                @if($editDependentId === $dependent->id)
                                    <select wire:model.defer="editRelationship"
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="spouse">Spouse</option>
                                        <option value="child">Child</option>
                                        <option value="parent">Parent</option>
                                        <option value="other">Other</option>
                                    </select>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $relPill }}">
                                        {{ ucfirst($rel) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-3">
                                @if($editDependentId === $dependent->id)
                                    <select wire:model.defer="editStatus"
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="active">Active</option>
                                        <option value="deceased">Deceased</option>
                                    </select>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $statusPill }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Profile Complete --}}
                            <td class="px-6 py-3">
                                @if($editDependentId === $dependent->id)
                                    <label class="inline-flex items-center gap-2">
                                        <input type="checkbox" wire:model.defer="editProfileComplete" class="rounded border-gray-300">
                                        <span class="text-sm text-gray-700">Complete</span>
                                    </label>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $profilePill }}">
                                        {{ $dependent->profile_completed ? 'Yes' : 'No' }}
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    @if($editDependentId === $dependent->id)
                                        <button
                                            type="button"
                                            wire:click="save"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition text-xs font-semibold"
                                            title="Save"
                                        >
                                            Save
                                        </button>

                                        <button
                                            type="button"
                                            wire:click="$set('editDependentId', null)"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition text-xs font-semibold"
                                            title="Cancel"
                                        >
                                            Cancel
                                        </button>
                                    @else
                                        <button
                                            type="button"
                                            wire:click="edit({{ $dependent->id }})"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-xs font-semibold"
                                            title="Edit"
                                        >
                                            Edit
                                        </button>

                                        <button
                                            type="button"
                                            wire:click="toggleComplete({{ $dependent->id }})"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600 transition text-xs font-semibold"
                                            title="Toggle Complete"
                                        >
                                            Toggle
                                        </button>

                                        <button
                                            type="button"
                                            @click="showDeleteModal = true; deleteId = {{ $dependent->id }}"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition text-xs font-semibold"
                                            title="Delete"
                                        >
                                            Delete
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                No dependents found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 sm:p-6 border-t border-gray-100">
            {{ $dependents->links() }}
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden divide-y">
        @forelse($dependents as $dependent)
            @php
                $rel = strtolower($dependent->relationship ?? 'other');
                $st = strtolower($dependent->status ?? 'active');
            @endphp

            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="font-semibold text-gray-900 break-words">{{ $dependent->name }}</div>
                        <div class="text-sm text-gray-600 break-words">
                            Member: {{ $dependent->member->user->name ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="text-xs text-gray-600">
                        {{ ucfirst($rel) }}
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 text-sm">
                    <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-700">
                        Status: {{ ucfirst($st) }}
                    </span>
                    <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-700">
                        Profile: {{ $dependent->profile_completed ? 'Yes' : 'No' }}
                    </span>
                </div>

                <div class="flex gap-2">
                    <button
                        type="button"
                        wire:click="edit({{ $dependent->id }})"
                        class="w-full px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-sm font-semibold"
                    >
                        Edit
                    </button>

                    <button
                        type="button"
                        wire:click="toggleComplete({{ $dependent->id }})"
                        class="w-full px-4 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600 transition text-sm font-semibold"
                    >
                        Toggle
                    </button>

                    <button
                        type="button"
                        @click="showDeleteModal = true; deleteId = {{ $dependent->id }}"
                        class="w-full px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition text-sm font-semibold"
                    >
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-500">
                <div class="text-4xl mb-2">👨‍👩‍👧‍👦</div>
                <p>No dependents found.</p>
            </div>
        @endforelse

        <div class="p-4 border-t border-gray-100">
            {{ $dependents->links() }}
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div
        x-cloak
        x-show="showDeleteModal"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
        <div
            class="w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden"
            @click.away="showDeleteModal = false"
        >
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Confirm Delete</h3>
                <button type="button" @click="showDeleteModal = false" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">
                    &times;
                </button>
            </div>

            <div class="p-6">
                <p class="text-gray-600">
                    Are you sure you want to delete this dependent? This action cannot be undone.
                </p>
            </div>

            <div class="px-6 py-4 border-t flex flex-col sm:flex-row gap-2 sm:justify-end">
                <button
                    type="button"
                    @click="showDeleteModal = false"
                    class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    wire:click="delete(deleteId)"
                    @click="showDeleteModal = false"
                    class="w-full sm:w-auto px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition font-semibold"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

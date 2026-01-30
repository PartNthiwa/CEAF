<div
    x-data="{ showDeleteModal: false, deleteId: null }"
    x-on:keydown.escape.window="showDeleteModal = false"
    class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6"
>
    {{-- Header + Navigation --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Beneficiaries</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Beneficiaries Management</h2>
            <p class="text-sm text-gray-600 mt-1">Manage beneficiaries linked to each member here.</p>
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

    {{-- Allocation Error Panel --}}
    @if($percentageErrorData)
        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                <div>
                    <h4 class="text-red-800 font-bold text-lg">
                        Allocation exceeds 100% for {{ $percentageErrorData['member'] }}
                    </h4>
                    <p class="text-sm text-red-700 mt-1">
                        Please reduce the allocation so the total becomes 100% or less.
                    </p>
                </div>

                <div class="bg-white/70 rounded-xl border border-red-100 p-3 text-sm text-gray-800">
                    <div><span class="font-semibold">Current total:</span> {{ $percentageErrorData['current_total'] }}%</div>
                    <div><span class="font-semibold">Remaining:</span> {{ $percentageErrorData['remaining'] }}%</div>
                    <div class="text-red-700 font-semibold mt-1">
                        Attempted: {{ $percentageErrorData['attempted'] }}%
                    </div>
                </div>
            </div>

            <div class="mt-4 bg-white rounded-xl border border-red-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-red-100 text-sm font-semibold text-gray-800">
                    Current Beneficiaries Allocation
                </div>

                <div class="divide-y">
                    @foreach($percentageErrorData['beneficiaries'] as $b)
                        <div class="px-4 py-3 flex items-center justify-between text-sm">
                            <span class="text-gray-800">{{ $b['name'] }}</span>
                            <span class="font-semibold text-gray-900">{{ $b['percentage'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
            <p class="text-sm text-gray-600 mt-1">Search by member or beneficiary name.</p>
        </div>

        <div class="p-4 sm:p-6">
            <input
                type="text"
                wire:model.debounce.300ms="search"
                placeholder="Search by member or beneficiary name..."
                class="w-full sm:max-w-md rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >
            <div wire:loading class="text-xs text-gray-500 mt-2">Loading...</div>
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
                        <th class="px-6 py-3 text-left font-medium">Beneficiary</th>
                        <th class="px-6 py-3 text-left font-medium">Contact</th>
                        <th class="px-6 py-3 text-left font-medium">Percentage</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($beneficiaries as $index => $beneficiary)
                        @php
                            $rowNo = $beneficiaries->firstItem() + $index;
                            $pct = (int) ($beneficiary->percentage ?? 0);

                            $pctPill = $pct >= 50
                                ? 'bg-green-50 text-green-700 ring-green-600/20'
                                : ($pct >= 25
                                    ? 'bg-blue-50 text-blue-700 ring-blue-600/20'
                                    : 'bg-gray-100 text-gray-700 ring-gray-500/20');
                        @endphp

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 text-gray-600">{{ $rowNo }}</td>

                            <td class="px-6 py-3 font-medium text-gray-900">
                                {{ $beneficiary->member->user->name ?? 'N/A' }}
                            </td>

                            {{-- Name --}}
                            <td class="px-6 py-3">
                                @if($editId === $beneficiary->id)
                                    <input
                                        type="text"
                                        wire:model.defer="editName"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                    >
                                @else
                                    <span class="font-medium text-gray-900">{{ $beneficiary->name }}</span>
                                @endif
                            </td>

                            {{-- Contact --}}
                            <td class="px-6 py-3">
                                @if($editId === $beneficiary->id)
                                    <input
                                        type="text"
                                        wire:model.defer="editContact"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                    >
                                @else
                                    <span class="text-gray-700">{{ $beneficiary->contact ?? '—' }}</span>
                                @endif
                            </td>

                            {{-- Percentage --}}
                            <td class="px-6 py-3">
                                @if($editId === $beneficiary->id)
                                    <input
                                        type="number"
                                        min="1"
                                        max="100"
                                        wire:model.defer="editPercentage"
                                        class="w-28 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                    >
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $pctPill }}">
                                        {{ $pct }}%
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    @if($editId === $beneficiary->id)
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
                                            wire:click="resetEdit"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition text-xs font-semibold"
                                            title="Cancel"
                                        >
                                            Cancel
                                        </button>
                                    @else
                                        <button
                                            type="button"
                                            wire:click="edit({{ $beneficiary->id }})"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-xs font-semibold"
                                            title="Edit"
                                        >
                                            Edit
                                        </button>

                                        <button
                                            type="button"
                                            @click="showDeleteModal = true; deleteId = {{ $beneficiary->id }}"
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
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                No beneficiaries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 sm:p-6 border-t border-gray-100">
            {{ $beneficiaries->links() }}
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden divide-y">
        @forelse($beneficiaries as $beneficiary)
            @php
                $pct = (int) ($beneficiary->percentage ?? 0);
            @endphp

            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="font-semibold text-gray-900 break-words">{{ $beneficiary->name }}</div>
                        <div class="text-sm text-gray-600 break-words">
                            Member: {{ $beneficiary->member->user->name ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-gray-600 break-words">
                            Contact: {{ $beneficiary->contact ?? '—' }}
                        </div>
                    </div>

                    <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-700">
                        {{ $pct }}%
                    </span>
                </div>

                <div class="flex gap-2">
                    <button
                        type="button"
                        wire:click="edit({{ $beneficiary->id }})"
                        class="w-full px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-sm font-semibold"
                    >
                        Edit
                    </button>

                    <button
                        type="button"
                        @click="showDeleteModal = true; deleteId = {{ $beneficiary->id }}"
                        class="w-full px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition text-sm font-semibold"
                    >
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-500">
                <div class="text-4xl mb-2">🧾</div>
                <p>No beneficiaries found.</p>
            </div>
        @endforelse

        <div class="p-4 border-t border-gray-100">
            {{ $beneficiaries->links() }}
        </div>
    </div>

    {{-- Delete Modal --}}
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
                    Are you sure you want to delete this beneficiary? This action cannot be undone.
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

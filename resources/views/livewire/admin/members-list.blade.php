<div
    x-data="{
        showForm: @entangle('showForm'),
        showDeleteModal: false,
        deleteId: null,
    }"
    x-on:open-modal.window="showForm = true"
    x-on:close-modal.window="showForm = false"
    x-on:keydown.escape.window="showForm = false; showDeleteModal = false"
    class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6"
>
    {{-- Header + Navigation --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div class="min-w-0">
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Members</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">
                Members Management
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Manage all members and their details here.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
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

            <button
                type="button"
                @click="showForm = true"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                       bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold
                       hover:bg-blue-700 transition"
            >
                + Add Member
            </button>
        </div>
    </div>

    {{-- Quick Navigation Pills --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.dependents') }}"
           class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-800 text-sm font-semibold hover:bg-gray-50 transition">
            Dependents
        </a>

        <a href="{{ route('admin.beneficiaries') }}"
           class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-800 text-sm font-semibold hover:bg-gray-50 transition">
            Beneficiaries
        </a>

        <a href="{{ route('admin.invite-members') }}"
           class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-800 text-sm font-semibold hover:bg-gray-50 transition">
            Invite Members
        </a>

        <a href="{{ route('admin.approve-members') }}"
           class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-800 text-sm font-semibold hover:bg-gray-50 transition">
            Pending Members
        </a>
    </div>

    {{-- Alerts --}}
    <div class="space-y-3">
        @if (session()->has('success'))
            <div class="rounded-2xl bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
            <p class="text-sm text-gray-600 mt-1">Search by name/email and filter by status.</p>
        </div>

        <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-700">Search</label>
                <input
                    type="text"
                    wire:model.debounce.300ms="search"
                    placeholder="Search by member name or email..."
                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                >
                <div wire:loading wire:target="search" class="text-xs text-gray-500 mt-2">
                    Searching...
                </div>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Status</label>
                <select
                    wire:model="status"
                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">All Statuses</option>
                    @foreach($availableStatuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
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
                        <th class="px-6 py-3 text-left font-medium">Name</th>
                        <th class="px-6 py-3 text-left font-medium">Email</th>
                        <th class="px-6 py-3 text-left font-medium">Status</th>
                        <th class="px-6 py-3 text-left font-medium">Member Since</th>
                        <th class="px-6 py-3 text-left font-medium">Role</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($members as $index => $member)
                        @php
                            $memberIndex = $members->firstItem() + $index;

                            $mStatus = strtolower($member->membership_status ?? 'unknown');
                            $statusPill = match($mStatus) {
                                'active' => 'bg-green-50 text-green-700 ring-green-600/20',
                                'late' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                'suspended' => 'bg-red-50 text-red-700 ring-red-600/20',
                                'terminated' => 'bg-gray-100 text-gray-700 ring-gray-500/20',
                                default => 'bg-gray-50 text-gray-600 ring-gray-500/20'
                            };

                            $joinDate = $member->join_date
                                ? \Carbon\Carbon::parse($member->join_date)->format('d M Y')
                                : '—';

                            $role = ucfirst($member->user->role ?? 'N/A');
                            $isDeceased = (bool) ($member->deceased ?? false);
                        @endphp

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 text-gray-600">{{ $memberIndex }}</td>

                            <td class="px-6 py-3 font-medium text-gray-900">
                                {{ $member->user->name }}
                                @if($isDeceased)
                                    <span class="ml-2 text-xs text-red-600 font-semibold">(Deceased)</span>
                                @endif
                            </td>

                            <td class="px-6 py-3 text-gray-700">
                                <span class="break-all">{{ $member->user->email }}</span>
                            </td>

                            <td class="px-6 py-3">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $statusPill }}">
                                    {{ ucfirst($mStatus) }}
                                </span>
                            </td>

                            <td class="px-6 py-3 text-gray-700">{{ $joinDate }}</td>

                            <td class="px-6 py-3 text-gray-700">{{ $role }}</td>

                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2" x-data="{ isDeceased: {{ $isDeceased ? 'true' : 'false' }} }">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $member->id }})"
                                        :disabled="isDeceased"
                                        :class="{ 'opacity-50 cursor-not-allowed': isDeceased }"
                                        class="inline-flex items-center justify-center px-3 py-2 rounded-lg
                                               bg-yellow-500 text-white hover:bg-yellow-600 transition text-xs font-semibold"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        @click="showDeleteModal = true; deleteId = {{ $member->id }}"
                                        :disabled="isDeceased"
                                        :class="{ 'opacity-50 cursor-not-allowed': isDeceased }"
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
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                No members found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 sm:p-6 border-t border-gray-100">
            {{ $members->links() }}
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden divide-y">
        @forelse($members as $index => $member)
            @php
                $memberIndex = $members->firstItem() + $index;

                $mStatus = strtolower($member->membership_status ?? 'unknown');
                $statusPill = match($mStatus) {
                    'active' => 'bg-green-50 text-green-700',
                    'late' => 'bg-yellow-50 text-yellow-800',
                    'suspended' => 'bg-red-50 text-red-700',
                    'terminated' => 'bg-gray-100 text-gray-700',
                    default => 'bg-gray-50 text-gray-600'
                };

                $joinDate = $member->join_date
                    ? \Carbon\Carbon::parse($member->join_date)->format('d M Y')
                    : '—';

                $role = ucfirst($member->user->role ?? 'N/A');
                $isDeceased = (bool) ($member->deceased ?? false);
            @endphp

            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 break-words">
                            {{ $memberIndex }}. {{ $member->user->name }}
                            @if($isDeceased)
                                <span class="ml-1 text-xs text-red-600 font-semibold">(Deceased)</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-600 break-all">{{ $member->user->email }}</p>
                    </div>

                    <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold {{ $statusPill }}">
                        {{ ucfirst($mStatus) }}
                    </span>
                </div>

                <div class="text-sm text-gray-700 space-y-1">
                    <div><span class="text-gray-500">Member Since:</span> {{ $joinDate }}</div>
                    <div><span class="text-gray-500">Role:</span> {{ $role }}</div>
                </div>

                <div class="flex gap-2" x-data="{ isDeceased: {{ $isDeceased ? 'true' : 'false' }} }">
                    <button
                        type="button"
                        wire:click="edit({{ $member->id }})"
                        :disabled="isDeceased"
                        :class="{ 'opacity-50 cursor-not-allowed': isDeceased }"
                        class="w-full px-4 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition text-sm font-semibold"
                    >
                        Edit
                    </button>

                    <button
                        type="button"
                        @click="showDeleteModal = true; deleteId = {{ $member->id }}"
                        :disabled="isDeceased"
                        :class="{ 'opacity-50 cursor-not-allowed': isDeceased }"
                        class="w-full px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition text-sm font-semibold"
                    >
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-500">
                No members found.
            </div>
        @endforelse

        <div class="p-4 border-t border-gray-100">
            {{ $members->links() }}
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    <div
        x-cloak
        x-show="showForm"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
        <div
            class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden"
            @click.away="showForm = false"
        >
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $isEdit ? 'Edit Member' : 'Add Member' }}
                </h3>

                <button type="button" @click="showForm = false" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">
                    &times;
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Name</label>
                    <input wire:model="name" type="text"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input wire:model="email" type="email"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Status</label>
                    <select wire:model="membership_status"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @foreach($availableStatuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div wire:loading wire:target="save" class="text-gray-500 text-sm">
                    Saving...
                </div>
            </div>

            <div class="px-6 py-4 border-t flex flex-col sm:flex-row gap-2 sm:justify-end">
                <button type="button"
                        @click="showForm = false"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                    Cancel
                </button>

                <button type="button"
                        wire:click="save"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition font-semibold">
                    {{ $isEdit ? 'Update' : 'Create' }}
                </button>
            </div>
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
                    Are you sure you want to delete this member and their user?
                </p>
            </div>

            <div class="px-6 py-4 border-t flex flex-col sm:flex-row gap-2 sm:justify-end">
                <button type="button"
                        @click="showDeleteModal = false"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
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

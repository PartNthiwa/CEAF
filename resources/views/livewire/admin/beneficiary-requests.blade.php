<div
    class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6"
    x-data="{ showPayloadModal: @entangle('modalOpen') }"
    x-on:keydown.escape.window="showPayloadModal = false"
>

    {{-- Header + Navigation --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                <div>
                    <div class="text-sm text-gray-600">
                        Home &gt; <span class="font-semibold text-gray-900">Beneficiary Change Requests</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">
                        Pending Beneficiary Change Requests
                    </h2>

                    <p class="text-sm text-gray-600 mt-1 max-w-2xl">
                        Review submitted beneficiary change requests. You can view the payload, approve, or reject.
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
            <div class="mt-4 space-y-3">
                @if (session()->has('success'))
                    <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="p-4 sm:p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Requests</h3>
                <p class="text-sm text-gray-600">All submitted beneficiary change requests</p>
            </div>
            <div class="text-sm text-gray-500">
                {{ method_exists($requests, 'total') ? $requests->total() . ' total' : '' }}
            </div>
        </div>

        {{-- Desktop --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Member</th>
                        <th class="px-6 py-3 text-left font-medium">Submitted</th>
                        <th class="px-6 py-3 text-left font-medium">Status</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($requests as $r)
                        @php
                            $status = strtolower($r->status ?? 'pending');

                            $statusPill = match($status) {
                                'pending' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                'approved' => 'bg-green-50 text-green-700 ring-green-600/20',
                                'rejected' => 'bg-red-50 text-red-700 ring-red-600/20',
                                default => 'bg-gray-50 text-gray-600 ring-gray-500/20'
                            };

                            $memberName = $r->member->user->name ?? 'N/A';
                            $submittedAt = $r->created_at?->format('d M Y H:i') ?? '—';
                        @endphp

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $memberName }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $submittedAt }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $statusPill }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if(method_exists($r, 'isPending') ? $r->isPending() : ($status === 'pending'))
                                    <div class="flex flex-wrap gap-2">
                                        <button
                                            type="button"
                                            wire:click="viewPayload({{ $r->id }})"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg
                                                   bg-gray-100 text-gray-800 hover:bg-gray-200 transition text-xs font-semibold"
                                        >
                                            View
                                        </button>

                                        <button
                                            type="button"
                                            wire:click="approve({{ $r->id }})"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg
                                                   bg-green-600 text-white hover:bg-green-700 transition text-xs font-semibold"
                                        >
                                            Approve
                                        </button>

                                        <button
                                            type="button"
                                            wire:click="reject({{ $r->id }})"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg
                                                   bg-red-600 text-white hover:bg-red-700 transition text-xs font-semibold"
                                        >
                                            Reject
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">No actions</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                No pending requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden divide-y">
            @forelse($requests as $r)
                @php
                    $status = strtolower($r->status ?? 'pending');

                    $statusPill = match($status) {
                        'pending' => 'bg-yellow-50 text-yellow-800',
                        'approved' => 'bg-green-50 text-green-700',
                        'rejected' => 'bg-red-50 text-red-700',
                        default => 'bg-gray-50 text-gray-600'
                    };

                    $memberName = $r->member->user->name ?? 'N/A';
                    $submittedAt = $r->created_at?->format('d M Y H:i') ?? '—';
                @endphp

                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="font-semibold text-gray-900 break-words">
                                {{ $memberName }}
                            </div>
                            <div class="text-sm text-gray-600">
                                Submitted: {{ $submittedAt }}
                            </div>
                        </div>

                        <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold {{ $statusPill }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>

                    @if(method_exists($r, 'isPending') ? $r->isPending() : ($status === 'pending'))
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button
                                type="button"
                                wire:click="viewPayload({{ $r->id }})"
                                class="w-full px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200 transition text-sm font-semibold"
                            >
                                View
                            </button>

                            <button
                                type="button"
                                wire:click="approve({{ $r->id }})"
                                class="w-full px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition text-sm font-semibold"
                            >
                                Approve
                            </button>

                            <button
                                type="button"
                                wire:click="reject({{ $r->id }})"
                                class="w-full px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition text-sm font-semibold"
                            >
                                Reject
                            </button>
                        </div>
                    @else
                        <div class="text-gray-400 italic text-sm">
                            No actions
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-10 text-center text-gray-500">
                    No pending requests.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="p-4 sm:p-6 border-t border-gray-100">
            {{ $requests->links() }}
        </div>
    </div>

    {{-- Payload Modal --}}
    <div
        x-cloak
        x-show="showPayloadModal"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
        <div
            class="w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden"
            @click.away="showPayloadModal = false; $wire.closeModal()"
        >
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    Requested Changes
                </h3>

                <button
                    type="button"
                    @click="showPayloadModal = false; $wire.closeModal()"
                    class="text-gray-400 hover:text-gray-700 text-2xl leading-none"
                >
                    &times;
                </button>
            </div>

            <div class="p-6">
                @if(!empty($currentPayload))
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium">Beneficiary</th>
                                    <th class="px-4 py-2 text-left font-medium">Contact</th>
                                    <th class="px-4 py-2 text-left font-medium">Percentage</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-800">
                                @foreach($currentPayload as $bId => $data)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900">
                                            {{ $data['name'] ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            <span class="break-all">{{ $data['contact'] ?? '—' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-indigo-50 text-indigo-700 font-semibold">
                                                {{ $data['percentage'] ?? '—' }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="rounded-xl bg-gray-50 border border-gray-200 p-4 text-gray-600 text-sm">
                        No details available.
                    </div>
                @endif
            </div>

            <div class="px-6 py-4 border-t flex flex-col sm:flex-row gap-2 sm:justify-end">
                <button
                    type="button"
                    @click="showPayloadModal = false; $wire.closeModal()"
                    class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition font-semibold"
                >
                    Close
                </button>
            </div>
        </div>
    </div>

</div>

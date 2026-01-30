<div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6">

    {{-- Header + Navigation --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div class="min-w-0">
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Event Requests</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">
                Event Requests
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Review submitted events, approve/reject, and manage payouts.
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
        </div>
    </div>

    {{-- Alerts --}}
    <div class="space-y-3">
        @if (session()->has('success'))
            <div class="rounded-2xl bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Search</h3>
            <p class="text-sm text-gray-600 mt-1">Search by person name (or related text).</p>
        </div>

        <div class="p-4 sm:p-6">
            <input
                type="text"
                wire:model.debounce.300ms="search"
                placeholder="Search by person..."
                class="w-full sm:max-w-lg rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >
            <div wire:loading wire:target="search" class="text-xs text-gray-500 mt-2">
                Searching...
            </div>
        </div>
    </div>

    {{-- Seed insufficient banner --}}
    @if ($seedInsufficient)
        @php $firstEvent = $events->first(); @endphp

        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="text-yellow-900">
                <div class="font-semibold">Insufficient Seed Fund</div>
                <div class="text-sm text-yellow-800 mt-1">
                    Please open a replenishment cycle to continue approving events.
                </div>
            </div>

            @if($firstEvent)
                <button
                    type="button"
                    wire:click="openReplenishment({{ $firstEvent->id }})"
                    class="w-full sm:w-auto inline-flex items-center justify-center
                           bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-semibold transition"
                >
                    Open Replenishment Cycle
                </button>
            @endif
        </div>
    @endif

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">#</th>
                        <th class="px-6 py-3 text-left font-medium">Member</th>
                        <th class="px-6 py-3 text-left font-medium">Person</th>
                        <th class="px-6 py-3 text-left font-medium">Event Type</th>
                        <th class="px-6 py-3 text-left font-medium">Amount</th>
                        <th class="px-6 py-3 text-left font-medium">Status</th>
                        <th class="px-6 py-3 text-left font-medium">Submitted</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                        <th class="px-6 py-3 text-left font-medium">Payout</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($events as $event)
                        @php
                            $status = strtolower($event->status ?? 'unknown');

                            $statusPill = match($status) {
                                'approved' => 'bg-green-50 text-green-700 ring-green-600/20',
                                'rejected' => 'bg-red-50 text-red-700 ring-red-600/20',
                                'submitted', 'under_review' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                default => 'bg-gray-50 text-gray-600 ring-gray-500/20',
                            };

                            $canAct = in_array($status, ['submitted', 'under_review'], true);
                            $cycleType = $event->payment_cycle_type ?? 'seed';
                            $amount = (float) ($event->approved_amount ?? 0);
                            $submitted = $event->created_at?->format('d M Y') ?? '—';
                            $memberName = $event->member->user->name ?? 'N/A';
                            $personName = $event->person->full_name ?? 'N/A';

                            $canPayout = (bool) ($event->ready_for_payout ?? false) && (($event->payout_status ?? null) !== 'success');
                        @endphp

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 text-gray-600">{{ $events->firstItem() + $loop->index }}</td>

                            <td class="px-6 py-3 font-medium text-gray-900">
                                {{ $memberName }}
                            </td>

                            <td class="px-6 py-3">
                                {{ $personName }}
                            </td>

                            <td class="px-6 py-3 capitalize">
                                {{ $cycleType }}
                            </td>

                            <td class="px-6 py-3 font-semibold">
                                KES {{ number_format($amount) }}
                            </td>

                            <td class="px-6 py-3">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $statusPill }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <td class="px-6 py-3 text-gray-700">
                                {{ $submitted }}
                            </td>

                            <td class="px-6 py-3">
                                @if($canAct)
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            wire:click="approve({{ $event->id }})"
                                            @if($seedInsufficient) disabled @endif
                                            class="px-3 py-2 rounded-lg text-white text-xs font-semibold transition
                                                {{ $seedInsufficient ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700' }}"
                                        >
                                            Approve
                                        </button>

                                        <button
                                            type="button"
                                            wire:click="reject({{ $event->id }})"
                                            class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition"
                                        >
                                            Reject
                                        </button>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">No actions</span>
                                @endif
                            </td>

                            <td class="px-6 py-3">
                                @if($canPayout)
                                    <button
                                        type="button"
                                        wire:click="processPayout({{ $event->id }})"
                                        class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition"
                                    >
                                        Process Payout
                                    </button>
                                @else
                                    <span class="text-sm text-gray-500">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-gray-500">
                                No events found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 sm:p-6 border-t border-gray-100">
            {{ $events->links() }}
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden divide-y">
        @forelse($events as $event)
            @php
                $status = strtolower($event->status ?? 'unknown');

                $statusPill = match($status) {
                    'approved' => 'bg-green-50 text-green-700',
                    'rejected' => 'bg-red-50 text-red-700',
                    'submitted', 'under_review' => 'bg-yellow-50 text-yellow-800',
                    default => 'bg-gray-50 text-gray-600',
                };

                $canAct = in_array($status, ['submitted', 'under_review'], true);
                $cycleType = $event->payment_cycle_type ?? 'seed';
                $amount = (float) ($event->approved_amount ?? 0);
                $submitted = $event->created_at?->format('d M Y') ?? '—';
                $memberName = $event->member->user->name ?? 'N/A';
                $personName = $event->person->full_name ?? 'N/A';

                $canPayout = (bool) ($event->ready_for_payout ?? false) && (($event->payout_status ?? null) !== 'success');
            @endphp

            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 break-words">{{ $memberName }}</p>
                        <p class="text-sm text-gray-600 break-words">Person: {{ $personName }}</p>
                    </div>

                    <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold {{ $statusPill }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>

                <div class="text-sm text-gray-700 space-y-1">
                    <div><span class="text-gray-500">Type:</span> <span class="capitalize">{{ $cycleType }}</span></div>
                    <div><span class="text-gray-500">Amount:</span> <span class="font-semibold">KES {{ number_format($amount) }}</span></div>
                    <div><span class="text-gray-500">Submitted:</span> {{ $submitted }}</div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                    @if($canAct)
                        <button
                            type="button"
                            wire:click="approve({{ $event->id }})"
                            @if($seedInsufficient) disabled @endif
                            class="w-full px-4 py-2 rounded-lg text-white text-sm font-semibold transition
                                {{ $seedInsufficient ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700' }}"
                        >
                            Approve
                        </button>

                        <button
                            type="button"
                            wire:click="reject({{ $event->id }})"
                            class="w-full px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition"
                        >
                            Reject
                        </button>
                    @else
                        <div class="text-sm text-gray-500">No actions available</div>
                    @endif

                    @if($canPayout)
                        <button
                            type="button"
                            wire:click="processPayout({{ $event->id }})"
                            class="w-full px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition"
                        >
                            Process Payout
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-500">
                No events found.
            </div>
        @endforelse

        <div class="p-4 border-t border-gray-100">
            {{ $events->links() }}
        </div>
    </div>
</div>

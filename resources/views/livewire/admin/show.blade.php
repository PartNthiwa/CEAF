<div class="min-h-screen w-full bg-gray-100 bg-center bg-no-repeat rounded-lg
            bg-[length:700px_auto] sm:bg-[length:900px_auto] lg:bg-[length:1200px_auto] 2xl:bg-[length:1600px_auto]"
     style="background-image: url('/images/beneficiary.png');">


    <div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-4 sm:py-6 space-y-6 sm:space-y-8
                bg-white/80 rounded-2xl shadow-lg">

        @php
            $status = strtolower($member->membership_status ?? 'unknown');

            $statusBadge = match ($status) {
                'active' => 'bg-blue-50 text-blue-700',
                'late' => 'bg-yellow-50 text-yellow-800',
                'suspended' => 'bg-red-50 text-red-700',
                'terminated' => 'bg-gray-100 text-gray-700',
                default => 'bg-gray-50 text-gray-600',
            };

            $name = $member->user->name ?? '—';
            $email = $member->user->email ?? '—';

            $amountDueDisplay = $amountDue > 0 ? '$' . number_format($amountDue, 2) : '—';
            $nextDeadlineDisplay = $nextDeadline ?? '—';

            $dependentsCount = $dependents->count();
            $beneficiariesCount = $beneficiaries->count();
            $eventsCount = $events->count();
            $paymentsCount = $payments->count();

            $benefTotal = $beneficiaryAllocationTotal;
        @endphp

        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="min-w-0">
                <div class="text-sm text-gray-600">
                    Home &gt; <span class="font-semibold text-gray-900">Member Profile</span>
                </div>

                <div class="mt-5 flex flex-wrap items-center gap-2 sm:gap-3">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 break-words">
                        {{ $name }}
                    </h2>

                    <span class="mt-2  inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold {{ $statusBadge }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>

                <p class="text-sm text-gray-600 mt-1 break-words">
                    Member Number:
                    <span class="font-semibold text-gray-900">{{ $member->member_number ?? '—' }}</span>
                    <span class="hidden sm:inline"> • </span>
                    <span class="block sm:inline">
                        Email:
                        <span class="font-semibold text-gray-900 break-all">{{ $email }}</span>
                    </span>
                </p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full lg:w-auto">
                <button type="button"
                        wire:click="goBack"
                        class="w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-800 font-semibold
                               px-4 py-2 rounded-lg border transition">
                    ← Back
                </button>

                <button type="button"
                        wire:click="refreshData"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold
                               px-4 py-2 rounded-lg transition">
                    Refresh
                </button>
            </div>
        </div>

        {{-- Summary Cards --}}
   <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 xl:gap-6 w-full">

    {{-- Outstanding --}}
    <div class="h-full rounded-2xl border bg-red-100 p-4 sm:p-5 shadow-sm
                flex flex-col justify-between">
        <div>
            <div class="text-xs sm:text-sm text-gray-600">Outstanding Balance</div>
            <div class="mt-1 text-xl sm:text-2xl font-bold text-gray-900 break-words">
                {{ $amountDueDisplay }}
            </div>
        </div>
        <div class="mt-3 text-xs text-gray-500">
            Total unpaid (including late fees)
        </div>
    </div>

    {{-- Deadline --}}
    <div class="h-full rounded-2xl border bg-blue-100 p-4 sm:p-5 shadow-sm
                flex flex-col justify-between">
        <div>
            <div class="text-xs sm:text-sm text-gray-600">Next Deadline</div>
            <div class="mt-1 text-xl sm:text-2xl font-bold text-gray-900 break-words">
                {{ $nextDeadlineDisplay }}
            </div>
        </div>
        <div class="mt-3 text-xs text-gray-500">
            Earliest unpaid cycle
        </div>
    </div>

    {{-- Dependents --}}
    <div class="h-full rounded-2xl border bg-purple-100 p-4 sm:p-5 shadow-sm
                flex flex-col justify-between">
        <div>
            <div class="text-xs sm:text-sm text-gray-600">Dependents</div>
            <div class="mt-1 text-xl sm:text-2xl font-bold text-gray-900">
                {{ $dependentsCount }}
            </div>
        </div>
        <div class="mt-3 text-xs text-gray-500">
            Registered dependents
        </div>
    </div>

    {{-- Beneficiaries --}}
    <div class="h-full rounded-2xl border bg-green-100 p-4 sm:p-5 shadow-sm
                flex flex-col justify-between">
        <div>
            <div class="text-xs sm:text-sm text-gray-600">Beneficiaries</div>
            <div class="mt-1 text-xl sm:text-2xl font-bold text-gray-900">
                {{ $beneficiariesCount }}
            </div>
        </div>
        <div class="mt-3 text-xs text-gray-500">
            Active beneficiary records
        </div>
    </div>

</div>


        {{-- Member Details --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b flex items-start sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Member Details</h3>
                    <p class="text-sm text-gray-400">Profile information</p>
                </div>
            </div>

            <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 text-sm">
                <div class="min-w-0">
                    <p class="text-gray-500">Full Name</p>
                    <p class="font-semibold text-gray-900 break-words">{{ $name }}</p>
                </div>

                <div class="min-w-0">
                    <p class="text-gray-500">Email</p>
                    <p class="font-semibold text-gray-900 break-all">{{ $email }}</p>
                </div>

                <div class="min-w-0">
                    <p class="text-gray-500">Member Number</p>
                    <p class="font-semibold text-gray-900 break-words">{{ $member->member_number ?? '—' }}</p>
                </div>

                <div class="min-w-0">
                    <p class="text-gray-500">Status</p>
                    <p class="font-semibold text-gray-900 capitalize">{{ $status }}</p>
                </div>

                <div class="min-w-0">
                    <p class="text-gray-500">Phone</p>
                    <p class="font-semibold text-gray-900 break-words">{{ $member->phone ?? '—' }}</p>
                </div>

                <div class="min-w-0">
                    <p class="text-gray-500">Location</p>
                    <p class="font-semibold text-gray-900 break-words">{{ $member->location ?? '—' }}</p>
                </div>

                <div class="sm:col-span-2 lg:col-span-3 min-w-0">
                    <p class="text-gray-500">Notes</p>
                    <p class="font-semibold text-gray-900 break-words">{{ $member->notes ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Beneficiaries --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Beneficiaries</h3>
                    <p class="text-sm text-gray-400">{{ $beneficiariesCount }} total</p>
                </div>
            </div>

            {{-- Allocation Progress --}}
            <div class="px-4 sm:px-6 py-4 border-b space-y-2">
                <div class="flex justify-between text-sm text-gray-600 gap-2">
                    <span>Total Allocation</span>
                    <span class="font-semibold whitespace-nowrap">{{ $benefTotal }}%</span>
                </div>

                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div
                        class="h-full transition-all {{ $benefTotal > 100 ? 'bg-red-500' : 'bg-indigo-600' }}"
                        style="width: {{ min($benefTotal, 100) }}%">
                    </div>
                </div>

                @if($benefTotal < 100)
                    <p class="text-xs text-amber-600">{{ 100 - $benefTotal }}% remaining to allocate</p>
                @elseif($benefTotal > 100)
                    <p class="text-xs text-red-600">Allocation exceeds 100%</p>
                @endif
            </div>

            <div class="divide-y">
                @forelse($beneficiaries as $b)
                    @php
                        $badge =
                            ($b->percentage ?? 0) >= 50 ? 'bg-green-50 text-green-700' :
                            (($b->percentage ?? 0) >= 25 ? 'bg-blue-50 text-blue-700' :
                            'bg-gray-100 text-gray-700');
                    @endphp

                    <div class="px-4 sm:px-6 py-4 flex items-start sm:items-center justify-between gap-3 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold shrink-0">
                                {{ strtoupper(substr($b->name ?? 'B', 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 break-words">{{ $b->name ?? '—' }}</p>
                                <p class="text-sm text-gray-500 break-all">{{ $b->contact ?? '—' }}</p>
                            </div>
                        </div>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $badge }} whitespace-nowrap">
                            {{ $b->percentage ?? 0 }}%
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-16 text-center space-y-2 text-gray-500">
                        <div class="text-4xl">🧾</div>
                        <p>No beneficiaries found for this member.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Dependents --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Dependents</h3>
                    <p class="text-sm text-gray-400">{{ $dependentsCount }} total</p>
                </div>
            </div>

            <div class="divide-y">
                @forelse($dependents as $d)
                    @php
                        $dStatus = strtolower($d->status ?? 'active');
                        $dBadge = match ($dStatus) {
                            'active' => 'bg-green-50 text-green-700',
                            'inactive' => 'bg-gray-100 text-gray-700',
                            'deceased' => 'bg-red-50 text-red-700',
                            default => 'bg-gray-50 text-gray-600',
                        };
                    @endphp

                    <div class="px-4 sm:px-6 py-4 flex items-start sm:items-center justify-between gap-3 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold shrink-0">
                                {{ strtoupper(substr($d->name ?? 'D', 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 break-words">{{ $d->name ?? '—' }}</p>
                                <p class="text-sm text-gray-500 break-words">{{ $d->relationship ?? 'Dependent' }}</p>
                            </div>
                        </div>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $dBadge }} whitespace-nowrap">
                            {{ ucfirst($dStatus) }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-16 text-center space-y-2 text-gray-500">
                        <div class="text-4xl">👨‍👩‍👧‍👦</div>
                        <p>No dependents found for this member.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Events --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Events</h3>
                    <p class="text-sm text-gray-400">{{ $eventsCount }} total</p>
                </div>
            </div>

            <div class="divide-y">
                @forelse($events as $e)
                    <div class="px-4 sm:px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-1 sm:gap-3">
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 break-words">
                                    {{ $e->title ?? ($e->type ?? 'Event') }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $e->created_at?->format('d M Y') ?? '—' }}
                                </p>
                            </div>
                            <span class="text-sm text-gray-600 whitespace-nowrap">
                                {{ $e->type ?? '—' }}
                            </span>
                        </div>

                        @if(!empty($e->description))
                            <p class="text-sm text-gray-600 mt-2 break-words">{{ $e->description }}</p>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-16 text-center space-y-2 text-gray-500">
                        <div class="text-4xl">🗓️</div>
                        <p>No events found for this member.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Payments --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Payments</h3>
                    <p class="text-sm text-gray-400">{{ $paymentsCount }} total</p>
                </div>
            </div>

            <div class="divide-y">
                @forelse($payments as $p)
                    @php
                        $expected = (float) ($p->amount_due ?? 0);
                        $paid     = (float) ($p->amount_paid ?? 0);
                        $lateFee  = (float) ($p->late_fee ?? 0);
                        $outstanding = max(0, ($expected + $lateFee) - $paid);

                        $pstatus = strtolower($p->status ?? 'unknown');
                        $pBadge = match ($pstatus) {
                            'paid' => 'bg-green-50 text-green-700',
                            'pending' => 'bg-yellow-50 text-yellow-800',
                            'late' => 'bg-red-50 text-red-700',
                            default => 'bg-gray-50 text-gray-600',
                        };
                    @endphp

                    <div class="px-4 sm:px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 break-words">
                                    {{ $p->paymentCycle?->name ?? 'Payment Cycle' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Due: {{ $p->paymentCycle?->due_date?->format('d M Y') ?? '—' }}
                                </p>

                                <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs text-gray-500">
                                    <div>Expected: <span class="font-semibold text-gray-700">${{ number_format($expected, 2) }}</span></div>
                                    <div>Paid: <span class="font-semibold text-gray-700">${{ number_format($paid, 2) }}</span></div>
                                    <div>Late Fee: <span class="font-semibold text-gray-700">${{ number_format($lateFee, 2) }}</span></div>
                                </div>
                            </div>

                            <div class="flex flex-row lg:flex-col lg:items-end lg:text-right justify-between lg:justify-start gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $pBadge }} whitespace-nowrap">
                                    {{ ucfirst($pstatus) }}
                                </span>

                                <div>
                                    <div class="text-xs text-gray-500">Outstanding</div>
                                    <div class="text-lg font-bold text-gray-900">
                                        ${{ number_format($outstanding, 2) }}
                                    </div>
                                </div>

                                <div class="text-xs text-gray-500">
                                    @if($p->paid_at)
                                        Paid: {{ \Carbon\Carbon::parse($p->paid_at)->format('d M Y') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-16 text-center space-y-2 text-gray-500">
                        <div class="text-4xl">💳</div>
                        <p>No payments found for this member.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="text-xs text-gray-500 pt-2">
            &copy; {{ now()->year }} Carolina East Africa Foundation
        </div>
    </div>
</div>

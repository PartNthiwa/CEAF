<div class="bg-gray-50 min-h-screen rounded-lg">
    <div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-8">

        {{-- Breadcrumb / Title --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Admin Dashboard</span>
            </div>

            <div class="mt-2 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        Admin Dashboard
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Overview of member and financial activity.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <a
                        href="{{ route('admin.members-list') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 rounded-lg
                               bg-white border border-gray-200 text-gray-800 font-semibold hover:bg-gray-50 transition"
                    >
                        Members
                    </a>

                    <button
                        type="button"
                        wire:click="enforceLatePayments"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 rounded-lg
                               bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition"
                    >
                        Enforce Late Payments
                    </button>
                </div>
            </div>
        </div>

        {{-- MEMBER HEALTH --}}
        <section class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Member Health</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <x-admin.stat
                    title="Total Members"
                    :value="$totalMembers"
                    href="{{ route('admin.members-list') }}"
                    color="blue"
                >
                    View →
                </x-admin.stat>

                <x-admin.stat title="Active" :value="$activeMembers" color="blue">
                    <button type="button" wire:click="filterStatus('active')"
                        class="text-blue-700 hover:underline font-medium">
                        Filter →
                    </button>
                </x-admin.stat>

                <x-admin.stat title="Late" :value="$lateMembers" color="yellow">
                    <button type="button" wire:click="filterStatus('late')"
                        class="text-yellow-800 hover:underline font-medium">
                        Filter →
                    </button>
                </x-admin.stat>

                <x-admin.stat title="Suspended" :value="$suspendedMembers" color="red">
                    <button type="button" wire:click="filterStatus('suspended')"
                        class="text-red-700 hover:underline font-medium">
                        Filter →
                    </button>
                </x-admin.stat>

                <x-admin.stat title="Terminated" :value="$terminatedMembers" color="gray">
                    <button type="button" wire:click="filterStatus('terminated')"
                        class="text-gray-700 hover:underline font-medium">
                        Filter →
                    </button>
                </x-admin.stat>
            </div>
        </section>

        {{-- FINANCIAL HEALTH --}}
        <section class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">
                Financial Health ({{ now()->year }})
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-admin.stat title="Seed Balance" :value="'$' . number_format($seedBalance)" color="blue" />
                <x-admin.stat title="Seed Spent" :value="'$' . number_format($seedSpent)" color="gray" />
                <x-admin.stat title="Open Replenishments" :value="$openReplenishments" color="yellow" />
            </div>
        </section>

        {{-- ACTION REQUIRED --}}
        @if($alerts->isNotEmpty())
            <section class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-yellow-900 mb-3">Action Required</h3>

                <ul class="space-y-2 text-sm text-yellow-900">
                    @foreach($alerts as $alert)
                        <li>• {{ $alert }}</li>
                    @endforeach
                </ul>
            </section>
        @endif

        {{-- MEMBERS LISTING --}}
        <section class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-100 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Members Listing</h2>
                    <p class="text-sm text-gray-600">Click a row to preview member info.</p>
                </div>
                <div class="text-sm text-gray-500">
                    {{ $members->total() }} total
                </div>
            </div>

            {{-- Desktop --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium">#</th>
                            <th class="px-6 py-3 text-left font-medium">Name</th>
                            <th class="px-6 py-3 text-left font-medium">Email</th>
                            <th class="px-6 py-3 text-left font-medium">Status</th>
                            <th class="px-6 py-3 text-left font-medium">Amount Due</th>
                            <th class="px-6 py-3 text-left font-medium">Next Deadline</th>
                            <th class="px-6 py-3 text-left font-medium">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 text-gray-800">
                        @foreach($members as $index => $member)
                            @php
                                $amountDue = $member->amount_due > 0 ? '$' . number_format($member->amount_due) : '—';
                                $deadline = $member->next_deadline?->format('d M Y') ?? '—';

                                $status = strtolower($member->membership_status ?? 'unknown');

                                $statusBadge = match ($status) {
                                    'active' => 'bg-green-50 text-green-700 ring-green-600/20',
                                    'late' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                    'suspended' => 'bg-red-50 text-red-700 ring-red-600/20',
                                    'terminated' => 'bg-gray-100 text-gray-700 ring-gray-500/20',
                                    default => 'bg-gray-50 text-gray-600 ring-gray-500/20',
                                };
                            @endphp

                            <tr
                                wire:click="openMember({{ $member->id }})"
                                class="cursor-pointer hover:bg-blue-50/60 transition"
                            >
                                <td class="px-6 py-3 text-gray-600">{{ $members->firstItem() + $index }}</td>
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $member->user->name }}</td>
                                <td class="px-6 py-3 text-gray-700 break-all">{{ $member->user->email }}</td>

                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $statusBadge }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-3">{{ $amountDue }}</td>
                                <td class="px-6 py-3">{{ $deadline }}</td>

                                <td class="px-6 py-3">
                                    <a
                                        href="#"
                                        wire:click.prevent.stop="openMember({{ $member->id }})"
                                        class="text-blue-600 hover:underline font-semibold"
                                    >
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="md:hidden divide-y">
                @foreach($members as $index => $member)
                    @php
                        $amountDue = $member->amount_due > 0 ? '$' . number_format($member->amount_due) : '—';
                        $deadline = $member->next_deadline?->format('d M Y') ?? '—';

                        $status = strtolower($member->membership_status ?? 'unknown');

                        $statusBadge = match ($status) {
                            'active' => 'bg-green-50 text-green-700',
                            'late' => 'bg-yellow-50 text-yellow-800',
                            'suspended' => 'bg-red-50 text-red-700',
                            'terminated' => 'bg-gray-100 text-gray-700',
                            default => 'bg-gray-50 text-gray-600',
                        };
                    @endphp

                    <div class="p-4 hover:bg-blue-50/60 transition cursor-pointer"
                         wire:click="openMember({{ $member->id }})"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 break-words">
                                    {{ $members->firstItem() + $index }}. {{ $member->user->name }}
                                </p>
                                <p class="text-sm text-gray-600 break-all">{{ $member->user->email }}</p>
                            </div>

                            <a href="#"
                               wire:click.prevent.stop="openMember({{ $member->id }})"
                               class="text-blue-600 hover:underline font-semibold"
                            >
                                View
                            </a>
                        </div>

                        <div class="mt-3 text-sm text-gray-700 space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500">Status:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold {{ $statusBadge }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>

                            <div><span class="text-gray-500">Amount Due:</span> {{ $amountDue }}</div>
                            <div><span class="text-gray-500">Next Deadline:</span> {{ $deadline }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="p-4 sm:p-6 border-t border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center gap-3">
                    <div class="text-sm text-gray-600">
                        Showing {{ $members->firstItem() }} to {{ $members->lastItem() }} of {{ $members->total() }} members
                    </div>

                    <div>{{ $members->links() }}</div>
                </div>
            </div>
        </section>

        {{-- MEMBER MODAL --}}
        @if($showMemberModal && $selectedMember)
            <div class="fixed inset-0 z-50">
                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-black/40" wire:click="closeMemberModal"></div>

                {{-- Modal wrapper (scroll safe) --}}
                <div class="relative flex min-h-screen items-end sm:items-center justify-center p-3 sm:p-6">
                    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">

                        <div class="p-5 border-b border-gray-100 flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Member Details</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Name: <span class="font-semibold text-gray-900">{{ $selectedMember->user->name }}</span><br>
                                    Email: <span class="font-semibold text-gray-900">{{ $selectedMember->user->email }}</span>
                                </p>
                            </div>

                            <button type="button"
                                    wire:click="closeMemberModal"
                                    class="text-gray-400 hover:text-gray-700 text-2xl leading-none"
                                    aria-label="Close"
                            >
                                &times;
                            </button>
                        </div>

                        <div class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">
                            @php
                                $status = strtolower($selectedMember->membership_status ?? 'unknown');
                                $statusBadge = match ($status) {
                                    'active' => 'bg-green-50 text-green-700 ring-green-600/20',
                                    'late' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                    'suspended' => 'bg-red-50 text-red-700 ring-red-600/20',
                                    'terminated' => 'bg-gray-100 text-gray-700 ring-gray-500/20',
                                    default => 'bg-gray-50 text-gray-600 ring-gray-500/20',
                                };
                            @endphp

                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $statusBadge }}">
                                    {{ ucfirst($status) }}
                                </span>

                                <span class="text-sm text-gray-600">
                                    Member Number: <span class="font-semibold text-gray-900">{{ $selectedMember->member_number ?? '—' }}</span>
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <div class="text-xs text-gray-500">Joined</div>
                                    <div class="font-medium text-gray-900">
                                        {{ optional($selectedMember->created_at)->format('d M Y') ?? '—' }}
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <div class="text-xs text-gray-500">Last Updated</div>
                                    <div class="font-medium text-gray-900">
                                        {{ optional($selectedMember->updated_at)->format('d M Y') ?? '—' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Payments preview --}}
                            <div class="border border-gray-100 rounded-xl overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 text-sm font-semibold text-gray-700">
                                    Recent Payments (latest 10)
                                </div>

                                <div class="divide-y">
                                    @forelse($selectedMember->payments as $payment)
                                        @php
                                            $due = (float) ($payment->amount_due ?? 0);
                                            $paid = (float) ($payment->amount_paid ?? 0);
                                            $lateFee = (float) ($payment->late_fee ?? 0);
                                            $outstanding = max(0, ($due + $lateFee) - $paid);

                                            $pstatus = strtolower($payment->status ?? 'unknown');
                                            $pBadge = match($pstatus) {
                                                'paid' => 'bg-green-50 text-green-700 ring-green-600/20',
                                                'pending' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                                'late' => 'bg-red-50 text-red-700 ring-red-600/20',
                                                default => 'bg-gray-50 text-gray-600 ring-gray-500/20',
                                            };
                                        @endphp

                                        <div class="p-4 flex items-start justify-between gap-4">
                                            <div class="min-w-0">
                                                <div class="text-sm font-medium text-gray-900 truncate">
                                                    Cycle: {{ $payment->paymentCycle?->name ?? '—' }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Due: {{ $payment->paymentCycle?->due_date?->format('d M Y') ?? '—' }}
                                                </div>

                                                <div class="mt-2 text-xs text-gray-600 space-y-1">
                                                    <div>Expected: <span class="font-semibold">${{ number_format($due, 2) }}</span></div>
                                                    <div>Paid: <span class="font-semibold">${{ number_format($paid, 2) }}</span></div>
                                                    <div>Late Fee: <span class="font-semibold">${{ number_format($lateFee, 2) }}</span></div>
                                                </div>
                                            </div>

                                            <div class="text-right shrink-0">
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $pBadge }}">
                                                    {{ ucfirst($pstatus) }}
                                                </span>

                                                <div class="mt-3">
                                                    <div class="text-xs text-gray-500">Outstanding</div>
                                                    <div class="text-lg font-bold text-gray-900">
                                                        ${{ number_format($outstanding, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-4 text-sm text-gray-600">No payments found.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="p-5 border-t border-gray-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2">
                            <button type="button"
                                    wire:click="closeMemberModal"
                                    class="w-full sm:w-auto px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"
                            >
                                Close
                            </button>

                            <a href="{{ route('admin.show', $selectedMember) }}"
                               class="w-full sm:w-auto px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-center font-semibold"
                            >
                                Open Full Profile
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        @endif

        <footer class="text-xs text-gray-500 pt-2">
            &copy; {{ now()->year }} Carolina East Africa Foundation
        </footer>
    </div>
</div>

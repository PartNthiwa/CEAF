@php
    $status = $memberStatus; // from Livewire component
@endphp

<div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

    {{-- Header --}}
    <div class="px-6 py-5 border-b">
        <h2 class="text-2xl font-bold text-gray-900">My Payments</h2>
        <p class="text-sm text-gray-600 mt-1">
            View your payment cycles, due dates, and settle outstanding balances.
        </p>
    </div>

    {{-- Membership Banner --}}
    <div class="px-6 pt-5">
        @if($status === 'active')
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                <div class="flex items-start gap-3">
                    <div class="text-lg leading-none">✅</div>
                    <div>
                        <p class="font-semibold">Membership Active</p>
                        <p class="text-sm mt-1">You have full access to member services.</p>
                    </div>
                </div>
            </div>
        @elseif(in_array($status, ['late','suspended','terminated']))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                <div class="flex items-start gap-3">
                    <div class="text-lg leading-none">⚠️</div>
                    <div>
                        <p class="font-semibold">Payment Issue: {{ ucfirst($status) }}</p>
                        <p class="text-sm mt-1">
                            Please clear outstanding payments to regain access.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-800">
                <div class="flex items-start gap-3">
                    <div class="text-lg leading-none">ℹ️</div>
                    <div>
                        <p class="font-semibold">Membership Status: {{ ucfirst($status ?? 'unknown') }}</p>
                        <p class="text-sm mt-1">If this looks incorrect, contact support.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Payments Table --}}
    <div class="px-6 py-5">
        <div class="overflow-x-auto rounded-xl border">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Payment Cycle</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Amount Due</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Late Fee</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Due Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($payments as $index => $payment)
                        @php
                            $cycleType = ucfirst($payment->paymentCycle->type ?? 'cycle');
                            $cycleYear = $payment->paymentCycle->year ?? '';
                            $dueDate = optional($payment->paymentCycle)->due_date
                                ? \Carbon\Carbon::parse($payment->paymentCycle->due_date)->format('d M Y')
                                : 'N/A';

                            $amount = (float) ($payment->amount_due ?? 0);
                            $lateFee = (float) ($payment->late_fee ?? 0);
                            $total = $amount + $lateFee;

                            $statusLabel = $payment->status ?? 'pending';

                            $badge = match($statusLabel) {
                                'paid'    => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                'late'    => 'bg-red-50 text-red-700 ring-red-200',
                                'pending' => 'bg-amber-50 text-amber-800 ring-amber-200',
                                default   => 'bg-gray-100 text-gray-700 ring-gray-200',
                            };
                        @endphp

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $index + 1 }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                {{ $cycleType }} @if($cycleYear) - {{ $cycleYear }} @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                ₦{{ number_format($amount, 2) }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                ₦{{ number_format($lateFee, 2) }}
                            </td>

                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                ₦{{ number_format($total, 2) }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $dueDate }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ring-1 {{ $badge }}">
                                    {{ ucfirst($statusLabel) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                @if($statusLabel !== 'paid')
                                    <button
                                        type="button"
                                        wire:click="payNow({{ $payment->id }})"
                                        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700
                                               text-white text-sm font-semibold transition
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        wire:loading.attr="disabled"
                                        wire:target="payNow"
                                    >
                                        Pay Now
                                    </button>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="space-y-2">
                                    <div class="text-4xl">💳</div>
                                    <p class="text-gray-800 font-semibold">No payments found</p>
                                    <p class="text-sm text-gray-500">When payment cycles are generated, they will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Optional pagination if $payments is paginated --}}
        @if(method_exists($payments, 'links'))
            <div class="pt-4">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>

<div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6">

    {{-- Header + Navigation --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div class="min-w-0">
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Payments Audit</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">
                Payments Audit
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Manage member payments, late fees, and payment cycles.
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

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Member</th>
                        <th class="px-6 py-3 text-left font-medium">Cycle</th>
                        <th class="px-6 py-3 text-left font-medium">Amount</th>
                        <th class="px-6 py-3 text-left font-medium">Late Fee</th>
                        <th class="px-6 py-3 text-left font-medium">Status</th>
                        <th class="px-6 py-3 text-left font-medium">Paid At</th>
                        <th class="px-6 py-3 text-left font-medium">Amount Paid</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($payments as $payment)
                        @php
                            $memberName = $payment->member->user->name ?? '—';
                            $cycleType  = ucfirst($payment->paymentCycle->type ?? '—');
                            $cycleYear  = $payment->paymentCycle->year ?? '—';

                            $amount = (float) ($payment->amount_due ?? 0);
                            $lateFee = (float) ($payment->late_fee ?? 0);

                            $status = strtolower($payment->status ?? 'unknown');

                            $statusPill = match($status) {
                                'paid' => 'bg-green-50 text-green-700 ring-green-600/20',
                                'pending' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                                'late' => 'bg-red-50 text-red-700 ring-red-600/20',
                                default => 'bg-gray-50 text-gray-600 ring-gray-500/20',
                            };

                            $paidAt = $payment->paid_at
                                ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y')
                                : '—';

                            // Your schema shows paypal_order_id (not paypal_payment_id)
                            $paypalId = $payment->paypal_order_id ?? $payment->paypal_payment_id ?? '—';
                        @endphp

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 font-medium text-gray-900">
                                {{ $memberName }}
                            </td>

                            <td class="px-6 py-3 text-gray-700">
                                {{ $cycleType }} {{ $cycleYear }}
                            </td>

                            <td class="px-6 py-3 font-semibold">
                                ${{ number_format($amount, 2) }}
                            </td>

                            <td class="px-6 py-3">
                                ${{ number_format($lateFee, 2) }}
                            </td>

                            <td class="px-6 py-3">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $statusPill }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <td class="px-6 py-3 text-gray-700">
                                {{ $paidAt }}
                            </td>

                            <td class="px-6 py-3 text-xs text-gray-600">
                                <span class="break-all">{{ $amountPaid  }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                No payments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 sm:p-6 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden divide-y">
        @forelse($payments as $payment)
            @php
                $memberName = $payment->member->user->name ?? '—';
                $cycleType  = ucfirst($payment->paymentCycle->type ?? '—');
                $cycleYear  = $payment->paymentCycle->year ?? '—';

                $amount = (float) ($payment->amount_due ?? 0);
                $lateFee = (float) ($payment->late_fee ?? 0);

                $status = strtolower($payment->status ?? 'unknown');

                $statusPill = match($status) {
                    'paid' => 'bg-green-50 text-green-700',
                    'pending' => 'bg-yellow-50 text-yellow-800',
                    'late' => 'bg-red-50 text-red-700',
                    default => 'bg-gray-50 text-gray-600',
                };

                $paidAt = $payment->paid_at
                    ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y')
                    : '—';

                $paypalId = $payment->paypal_order_id ?? $payment->paypal_payment_id ?? '—';
            @endphp

            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 break-words">{{ $memberName }}</p>
                        <p class="text-sm text-gray-600">
                            Cycle: {{ $cycleType }} {{ $cycleYear }}
                        </p>
                    </div>

                    <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold {{ $statusPill }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>

                <div class="text-sm text-gray-700 space-y-1">
                    <div>
                        <span class="text-gray-500">Amount:</span>
                        <span class="font-semibold">${{ number_format($amount, 2) }}</span>
                    </div>

                    <div>
                        <span class="text-gray-500">Late Fee:</span>
                        ${{ number_format($lateFee, 2) }}
                    </div>

                    <div>
                        <span class="text-gray-500">Paid At:</span>
                        {{ $paidAt }}
                    </div>

                    <div class="text-xs text-gray-600">
                        <span class="text-gray-500">Amount Paid:</span>
                        <span class="break-all">{{$amountPaid }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-500">
                No payments found.
            </div>
        @endforelse

        <div class="p-4 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    </div>
</div>

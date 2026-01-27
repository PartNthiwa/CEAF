@php
    $status = $memberStatus; // from Livewire component
@endphp

<div class="p-6 bg-white rounded shadow space-y-4">

    <h2 class="text-2xl font-bold mb-4">My Payments</h2>

    {{-- Membership Warning Banner --}}
    @if($status === 'active')
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            Your membership is <strong>Active</strong>.
        </div>
    @elseif(in_array($status, ['late','suspended','terminated']))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            Your payment is <strong>{{ ucfirst($status) }}</strong>.
            Please clear outstanding payments to regain access.
        </div>
    @endif

    {{-- Payments Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-50">
                <tr>
                    <th>#</th>
                    <th>Payment Cycle</th>
                    <th>Amount Due</th>
                    <th>Late Fee</th>
                    <th>Total</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $index => $payment)
                    <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ ucfirst($payment->paymentCycle->type) }} - {{ $payment->paymentCycle->year }}</td>
                        <td>₦{{ number_format($payment->amount_due, 2) }}</td>
                        <td>₦{{ number_format($payment->late_fee, 2) }}</td>
                        <td>₦{{ number_format($payment->amount_due + $payment->late_fee, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->paymentCycle->due_date)->format('d M Y') }}</td>
                        <td class="capitalize">
                            @if($payment->status === 'paid')
                                <span class="text-green-700 font-semibold">Paid</span>
                            @elseif($payment->status === 'late')
                                <span class="text-red-600 font-semibold">Late</span>
                            @else
                                <span class="text-yellow-600 font-semibold">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->status !== 'paid')
                                <button wire:click="payNow({{ $payment->id }})"
                                        class="ml-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                    Pay Now
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 py-3">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

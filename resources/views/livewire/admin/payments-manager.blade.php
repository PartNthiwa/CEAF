

<div class="p-6 bg-white rounded shadow">

    <h2 class="text-2xl font-bold mb-4">Payments Audit</h2>

    <p class="text-gray-600">Here you can manage member payments, late fees, and payment cycles.</p>


    <table class="min-w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Member</th>
                <th class="p-2">Cycle</th>
                <th class="p-2">Amount</th>
                <th class="p-2">Late Fee</th>
                <th class="p-2">Status</th>
                <th class="p-2">Paid At</th>
                <th class="p-2">PayPal ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                <tr class="border-t">
                    <td class="p-2">
                        {{ $payment->member->user->name }}
                    </td>
                    <td class="p-2">
                        {{ ucfirst($payment->paymentCycle->type) }} {{ $payment->paymentCycle->year }}
                    </td>
                    <td class="p-2">
                        ₦{{ number_format($payment->amount_due, 2) }}
                    </td>
                    <td class="p-2">
                        ₦{{ number_format($payment->late_fee, 2) }}
                    </td>
                    <td class="p-2 capitalize">
                        {{ $payment->status }}
                    </td>
                    <td class="p-2">
                        {{ optional($payment->paid_at)->format('d M Y') ?? '—' }}
                    </td>
                    <td class="p-2 text-xs">
                        {{ $payment->paypal_payment_id ?? '—' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>
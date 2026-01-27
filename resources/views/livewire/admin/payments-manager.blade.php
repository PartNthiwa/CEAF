<div class="p-6 bg-white rounded shadow">

    <h2 class="text-2xl font-bold mb-4 text-gray-800">Payments Audit</h2>

    <p class="text-gray-600 mb-6">Here you can manage member payments, late fees, and payment cycles.</p>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 table-auto">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-3 text-left text-sm font-medium">Member</th>
                    <th class="p-3 text-left text-sm font-medium">Cycle</th>
                    <th class="p-3 text-left text-sm font-medium">Amount</th>
                    <th class="p-3 text-left text-sm font-medium">Late Fee</th>
                    <th class="p-3 text-left text-sm font-medium">Status</th>
                    <th class="p-3 text-left text-sm font-medium">Paid At</th>
                    <th class="p-3 text-left text-sm font-medium">PayPal ID</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($payments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 text-sm">
                            {{ $payment->member->user->name }}
                        </td>
                        <td class="p-3 text-sm">
                            {{ ucfirst($payment->paymentCycle->type) }} {{ $payment->paymentCycle->year }}
                        </td>
                        <td class="p-3 text-sm">
                            ${{ number_format($payment->amount_due, 2) }}
                        </td>
                        <td class="p-3 text-sm">
                            ${{ number_format($payment->late_fee, 2) }}
                        </td>
                        <td class="p-3 text-sm capitalize">
                            <span class="{{ $payment->status === 'paid' ? 'text-green-600' : 'text-red-600' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="p-3 text-sm">
                            {{ optional($payment->paid_at)->format('d M Y') ?? '—' }}
                        </td>
                        <td class="p-3 text-xs">
                            {{ $payment->paypal_payment_id ?? '—' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $payments->links() }}
    </div>
</div>

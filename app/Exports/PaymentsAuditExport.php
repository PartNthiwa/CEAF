<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsAuditExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private Builder $query) {}

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Member Name',
            'Member Email',
            'Cycle Type',
            'Year',
            'Amount Due',
            'Late Fee',
            'Status',
            'Paid At',
             'Amount Paid',
            'PayPal ID',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->member?->user?->name ?? '—',
            $payment->member?->user?->email ?? '—',
            $payment->paymentCycle?->type ?? '—',
            $payment->paymentCycle?->year ?? '—',
            (float) ($payment->amount_due ?? 0),
            (float) ($payment->late_fee ?? 0),
            $payment->status ?? '—',
            optional($payment->paid_at)?->format('Y-m-d H:i:s') ?? '—',
             (float) ($payment->amount_paid ?? 0),
            $payment->paypal_payment_id ?? '—',
        ];
    }
}

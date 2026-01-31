<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BeneficiariesAuditExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private Builder $query) {}

    public function query()
    {
        return $this->query->with(['member.user', 'person']);
    }

    public function headings(): array
    {
        return [
            'Beneficiary ID',
            'Member Name',
            'Member Email',
            'Beneficiary Name',
            'Beneficiary Contact',
            'Percentage',
            'Linked Person',
            'Person Contact',
        ];
    }

    public function map($b): array
    {
        $personName = $b->person?->full_name
            ?: trim(($b->person?->first_name ?? '') . ' ' . ($b->person?->last_name ?? ''))
            ?: '—';

        return [
            $b->id,
            $b->member?->user?->name ?? '—',
            $b->member?->user?->email ?? '—',
            $b->name ?? '—',
            $b->contact ?? '—',
            (float) ($b->percentage ?? 0),
            $personName,
            $b->person?->contact ?? '—',
        ];
    }
}

<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DependentsAuditExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private Builder $query) {}

    public function query()
    {
        return $this->query->with(['member.user', 'person']);
    }

    public function headings(): array
    {
        return [
            'Dependent ID',
            'Member Name',
            'Member Email',
            'Dependent Name',
            'Relationship',
            'Status',
            'Profile Completed',
            'Linked Person',
            'Person Contact',
        ];
    }

    public function map($d): array
    {
        $personName = $d->person?->full_name
            ?: trim(($d->person?->first_name ?? '') . ' ' . ($d->person?->last_name ?? ''))
            ?: '—';

        return [
            $d->id,
            $d->member?->user?->name ?? '—',
            $d->member?->user?->email ?? '—',
            $d->name ?? '—',
            $d->relationship ?? '—',
            $d->status ?? '—',
            ($d->profile_completed ?? 0) ? 'Yes' : 'No',
            $personName,
            $d->person?->contact ?? '—',
        ];
    }
}

<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MembersAuditExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private Builder $query) {}

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Member #',
            'Member Name',
            'Member Email',
            'Membership Status',
            'Join Date',
            'Status Changed At',
            'Approved At',
            'Approved By',
        ];
    }

    public function map($m): array
    {
        return [
            $m->member_number ?? '—',
            $m->user?->name ?? '—',
            $m->user?->email ?? '—',
            $m->membership_status ?? '—',
            optional($m->join_date)?->format('Y-m-d') ?? '—',
            optional($m->status_changed_at)?->format('Y-m-d H:i:s') ?? '—',
            optional($m->approved_at)?->format('Y-m-d H:i:s') ?? '—',
            $m->approved_by ?? '—',
        ];
    }
}

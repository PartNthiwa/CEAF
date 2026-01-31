<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EventsAuditExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private Builder $query) {}

    public function query()
    {
        // Make sure relationships are eager loaded for mapping (prevents N+1 / null surprises)
        return $this->query->with(['member.user', 'person', 'details']);
    }

    public function headings(): array
    {
        return [
            'Event ID',
            'Member Name',
            'Member Email',
            'Person Full Name',
            'Person Contact',
            'Event Title',
            'Event Date',
            'Location',
            'Status',
            'Approved At',
            'Approved Amount',
            'Paid From Seed',
            'Requires Replenishment',
        ];
    }

    public function map($e): array
    {
        $personName = $e->person?->full_name
            ?: trim(($e->person?->first_name ?? '') . ' ' . ($e->person?->last_name ?? ''))
            ?: '—';

        return [
            $e->id,
            $e->member?->user?->name ?? '—',
            $e->member?->user?->email ?? '—',

            $personName,
            $e->person?->contact ?? '—',

            $e->details?->title ?? '—',
            optional($e->details?->event_date)?->format('Y-m-d') ?? '—',
            $e->details?->location ?? '—',

            $e->status ?? '—',
            optional($e->approved_at)?->format('Y-m-d H:i:s') ?? '—',
            (float) ($e->approved_amount ?? 0),

            ($e->paid_from_seed ?? 0) ? 'Yes' : 'No',
            ($e->requires_replenishment ?? 0) ? 'Yes' : 'No',
        ];
    }
}

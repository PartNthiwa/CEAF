<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventsAuditExport;

class EventsReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->filters($request);

        $totalCount = Event::query()->count();

        $filteredQuery = $this->buildQuery($filters);
        $filteredCount = (clone $filteredQuery)->count();

        $events = $filteredQuery
            ->orderByDesc('approved_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.events.index', [
            'events' => $events,
            'filters' => $filters,
            'totalCount' => $totalCount,
            'filteredCount' => $filteredCount,
        ]);
    }

    public function export(Request $request)
    {
        $filters = $this->filters($request);
        $format = strtolower($request->get('format', 'xlsx'));

        $query = $this->buildQuery($filters)
            ->orderByDesc('approved_at')
            ->orderByDesc('id');

        if (in_array($format, ['xlsx', 'csv'])) {
            $fileName = 'events_report_' . now()->format('Ymd_His') . '.' . $format;
            return Excel::download(new EventsAuditExport($query), $fileName);
        }

        if ($format === 'pdf') {
            $rows = $query->get();

            $pdf = Pdf::loadView('admin.reports.events.pdf', [
                'rows' => $rows,
                'filters' => $filters,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('events_report_' . now()->format('Ymd_His') . '.pdf');
        }

        abort(400, 'Invalid export format.');
    }

    private function buildQuery(array $filters)
    {
        return Event::query()
            ->with(['member.user', 'person', 'details'])

            ->when($filters['status'], function ($q) use ($filters) {
                $q->whereRaw('LOWER(status) = ?', [strtolower($filters['status'])]);
            })

            ->when($filters['approved_only'], fn($q) => $q->whereNotNull('approved_at'))

            ->when(!is_null($filters['paid_from_seed']), function ($q) use ($filters) {
                $q->where('paid_from_seed', $filters['paid_from_seed']);
            })

            ->when(!is_null($filters['requires_replenishment']), function ($q) use ($filters) {
                $q->where('requires_replenishment', $filters['requires_replenishment']);
            })

            ->when($filters['member_search'], function ($q) use ($filters) {
                $s = $filters['member_search'];
                $q->whereHas('member.user', function ($u) use ($s) {
                    $u->where(function ($u) use ($s) {
                        $u->where('name', 'like', "%{$s}%")
                          ->orWhere('email', 'like', "%{$s}%");
                    });
                });
            })

            // ✅ Person search based on your fields
            ->when($filters['person_search'], function ($q) use ($filters) {
                $s = $filters['person_search'];
                $q->whereHas('person', function ($p) use ($s) {
                    $p->where(function ($p) use ($s) {
                        $p->where('first_name', 'like', "%{$s}%")
                          ->orWhere('last_name', 'like', "%{$s}%")
                          ->orWhere('contact', 'like', "%{$s}%");
                    });
                });
            })

            // ✅ Date range uses EventDetail.event_date
            ->when($filters['from'], fn($q) => $q->whereHas('details', fn($d) => $d->whereDate('event_date', '>=', $filters['from'])))
            ->when($filters['to'], fn($q) => $q->whereHas('details', fn($d) => $d->whereDate('event_date', '<=', $filters['to'])));
    }

    private function filters(Request $request): array
    {
        $boolOrNull = function (string $key) use ($request) {
            if (!$request->has($key) || $request->get($key) === '') return null;
            return filter_var($request->get($key), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        };

        return [
            'status' => $request->string('status')->toString() ?: null,
            'approved_only' => $request->boolean('approved_only'),

            'paid_from_seed' => $boolOrNull('paid_from_seed'),
            'requires_replenishment' => $boolOrNull('requires_replenishment'),

            'member_search' => trim((string) $request->get('member_search', '')) ?: null,
            'person_search' => trim((string) $request->get('person_search', '')) ?: null,

            'from' => $request->get('from') ?: null,
            'to' => $request->get('to') ?: null,
        ];
    }
}

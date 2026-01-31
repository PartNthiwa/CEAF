<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersAuditExport;

class MembersReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->filters($request);

        $baseQuery = Member::query();
        $totalCount = $baseQuery->count();

        $filteredQuery = $this->buildQuery($filters);
        $filteredCount = (clone $filteredQuery)->count();

        $members = $filteredQuery
            ->orderByDesc('join_date')             
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.members.index', [
            'members' => $members,
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
            ->orderByDesc('join_date')
            ->orderByDesc('id');

        if (in_array($format, ['xlsx', 'csv'])) {
            $fileName = 'CEAF_Member_Report_' . now()->format('Ymd_His') . '.' . $format;
            return Excel::download(new MembersAuditExport($query), $fileName);
        }

        if ($format === 'pdf') {
            $rows = $query->get();

            $pdf = Pdf::loadView('admin.reports.members.pdf', [
                'rows' => $rows,
                'filters' => $filters,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('CEAF_Member_Report_' . now()->format('Ymd_His') . '.pdf');
        }

        abort(400, 'Invalid export format.');
    }

    private function buildQuery(array $filters)
    {
        return Member::query()
            ->with(['user'])

            // Year filter uses join_date
            ->when($filters['year'], fn($q) => $q->whereYear('join_date', $filters['year']))

            // membership_status filter (case-insensitive safety)
            ->when($filters['membership_status'], function ($q) use ($filters) {
                $q->whereRaw('LOWER(membership_status) = ?', [strtolower($filters['membership_status'])]);
            })

            // Approved filter: approved_only=1
            ->when($filters['approved_only'], fn($q) => $q->whereNotNull('approved_at'))

            // member number exact/partial
            ->when($filters['member_number'], function ($q) use ($filters) {
                $q->where('member_number', 'like', '%' . $filters['member_number'] . '%');
            })

            // Search (user name/email)
            ->when($filters['member_search'], function ($q) use ($filters) {
                $s = $filters['member_search'];
                $q->whereHas('user', function ($u) use ($s) {
                    $u->where(function ($u) use ($s) {
                        $u->where('name', 'like', "%{$s}%")
                          ->orWhere('email', 'like', "%{$s}%");
                    });
                });
            })

            // Join date range (join_date)
            ->when($filters['from'], fn($q) => $q->whereDate('join_date', '>=', $filters['from']))
            ->when($filters['to'], fn($q) => $q->whereDate('join_date', '<=', $filters['to']));
    }

    private function filters(Request $request): array
    {
        return [
            'year' => $request->integer('year') ?: null,

            'membership_status' => $request->string('membership_status')->toString() ?: null, // active/late/etc

            'member_number' => trim((string) $request->get('member_number', '')) ?: null,

            'member_search' => trim((string) $request->get('member_search', '')) ?: null,

            'approved_only' => $request->boolean('approved_only'),

            'from' => $request->get('from') ?: null, // YYYY-MM-DD (join_date)
            'to' => $request->get('to') ?: null,
        ];
    }
}

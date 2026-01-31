<?php

namespace App\Http\Controllers;

use App\Models\Dependent;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DependentsAuditExport;

class DependentsReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->filters($request);

        $totalCount = Dependent::query()->count();

        $filteredQuery = $this->buildQuery($filters);
        $filteredCount = (clone $filteredQuery)->count();

        $dependents = $filteredQuery
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.dependents.index', [
            'dependents' => $dependents,
            'filters' => $filters,
            'totalCount' => $totalCount,
            'filteredCount' => $filteredCount,
        ]);
    }

    public function export(Request $request)
    {
        $filters = $this->filters($request);
        $format = strtolower($request->get('format', 'xlsx'));

        $query = $this->buildQuery($filters)->orderByDesc('id');

        if (in_array($format, ['xlsx', 'csv'])) {
            $fileName = 'CEAF_Dependents_Report_' . now()->format('Ymd_His') . '.' . $format;
            return Excel::download(new DependentsAuditExport($query), $fileName);
        }

        if ($format === 'pdf') {
            $rows = $query->get();

            $pdf = Pdf::loadView('admin.reports.dependents.pdf', [
                'rows' => $rows,
                'filters' => $filters,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('CEAF_Dependents_Report_' . now()->format('Ymd_His') . '.pdf');
        }

        abort(400, 'Invalid export format.');
    }

    private function buildQuery(array $filters)
    {
        return Dependent::query()
            ->with(['member.user', 'person'])

            // Member join year (optional, if member has join_date)
            ->when($filters['member_year'], function ($q) use ($filters) {
                $q->whereHas('member', fn($m) => $m->whereYear('join_date', $filters['member_year']));
            })

            // Status filter
            ->when($filters['status'], function ($q) use ($filters) {
                $q->whereRaw('LOWER(status) = ?', [strtolower($filters['status'])]);
            })

            // Profile completed (tri-state)
            ->when(!is_null($filters['profile_completed']), function ($q) use ($filters) {
                $q->where('profile_completed', $filters['profile_completed']);
            })

            // Relationship filter (string match)
            ->when($filters['relationship'], function ($q) use ($filters) {
                $q->where('relationship', 'like', '%' . $filters['relationship'] . '%');
            })

            // Dependent name search (dependents table)
            ->when($filters['dependent_search'], function ($q) use ($filters) {
                $s = $filters['dependent_search'];
                $q->where('name', 'like', "%{$s}%");
            })

            // Member search: user name/email
            ->when($filters['member_search'], function ($q) use ($filters) {
                $s = $filters['member_search'];
                $q->whereHas('member.user', function ($u) use ($s) {
                    $u->where(function ($u) use ($s) {
                        $u->where('name', 'like', "%{$s}%")
                          ->orWhere('email', 'like', "%{$s}%");
                    });
                });
            })

            // Person search (persons table)
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

            // Has linked person (tri-state)
            ->when($filters['has_person'] === true, fn($q) => $q->whereNotNull('person_id'))
            ->when($filters['has_person'] === false, fn($q) => $q->whereNull('person_id'));
    }

    private function filters(Request $request): array
    {
        $boolOrNull = function (string $key) use ($request) {
            if (!$request->has($key) || $request->get($key) === '') return null;
            return filter_var($request->get($key), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        };

        return [
            'member_year' => $request->integer('member_year') ?: null,

            'status' => $request->string('status')->toString() ?: null,
            'relationship' => trim((string) $request->get('relationship', '')) ?: null,

            'profile_completed' => $boolOrNull('profile_completed'), // '', 1, 0
            'has_person' => $boolOrNull('has_person'),               // '', 1, 0

            'member_search' => trim((string) $request->get('member_search', '')) ?: null,
            'dependent_search' => trim((string) $request->get('dependent_search', '')) ?: null,
            'person_search' => trim((string) $request->get('person_search', '')) ?: null,
        ];
    }
}

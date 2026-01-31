<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BeneficiariesAuditExport;

class BeneficiariesReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->filters($request);

        $totalCount = Beneficiary::query()->count();

        $filteredQuery = $this->buildQuery($filters);
        $filteredCount = (clone $filteredQuery)->count();

        $beneficiaries = $filteredQuery
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.beneficiaries.index', [
            'beneficiaries' => $beneficiaries,
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
            $fileName = 'beneficiaries_report_' . now()->format('Ymd_His') . '.' . $format;
            return Excel::download(new BeneficiariesAuditExport($query), $fileName);
        }

        if ($format === 'pdf') {
            $rows = $query->get();

            $pdf = Pdf::loadView('admin.reports.beneficiaries.pdf', [
                'rows' => $rows,
                'filters' => $filters,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('beneficiaries_report_' . now()->format('Ymd_His') . '.pdf');
        }

        abort(400, 'Invalid export format.');
    }

    private function buildQuery(array $filters)
    {
        return Beneficiary::query()
            ->with(['member.user', 'person'])

            // Filter by member join year (optional)
            ->when($filters['member_year'], function ($q) use ($filters) {
                $q->whereHas('member', fn($m) => $m->whereYear('join_date', $filters['member_year']));
            })

            // Beneficiary name/contact search (beneficiaries table)
            ->when($filters['beneficiary_search'], function ($q) use ($filters) {
                $s = $filters['beneficiary_search'];
                $q->where(function ($q) use ($s) {
                    $q->where('name', 'like', "%{$s}%")
                      ->orWhere('contact', 'like', "%{$s}%");
                });
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

            // Person search (persons table) - safe if person null (uses whereHas)
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

            // Percent range filters (optional)
            ->when(!is_null($filters['pct_min']), fn($q) => $q->where('percentage', '>=', $filters['pct_min']))
            ->when(!is_null($filters['pct_max']), fn($q) => $q->where('percentage', '<=', $filters['pct_max']))

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

        $pct = function (string $key) use ($request) {
            $v = $request->get($key);
            if ($v === null || $v === '') return null;
            return is_numeric($v) ? (float)$v : null;
        };

        return [
            'member_year' => $request->integer('member_year') ?: null,

            'member_search' => trim((string) $request->get('member_search', '')) ?: null,
            'beneficiary_search' => trim((string) $request->get('beneficiary_search', '')) ?: null,
            'person_search' => trim((string) $request->get('person_search', '')) ?: null,

            'pct_min' => $pct('pct_min'),
            'pct_max' => $pct('pct_max'),

            // tri-state: '', 1, 0
            'has_person' => $boolOrNull('has_person'),
        ];
    }
}

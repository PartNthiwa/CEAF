<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsAuditExport;
use Barryvdh\DomPDF\Facade\Pdf;

  use Illuminate\Support\Facades\DB;


class PaymentsReportController extends Controller
{
    public function index(Request $request)
{
    $filters = $this->filters($request);

    $baseQuery = Payment::query(); 
    $totalCount = $baseQuery->count();

    $filteredQuery = $this->buildQuery($filters);
    $filteredCount = (clone $filteredQuery)->count();

    $payments = $filteredQuery
        ->orderByDesc('paid_at')
        ->paginate(20)
        ->withQueryString();

    return view('admin.reports.payments.index', [
        'payments' => $payments,
        'filters' => $filters,
        'totalCount' => $totalCount,
        'filteredCount' => $filteredCount,
    ]);
}


    public function export(Request $request)
    {
        $filters = $this->filters($request);
        $format = strtolower($request->get('format', 'xlsx'));

        $query = $this->buildQuery($filters)->orderByDesc('paid_at');

        if (in_array($format, ['xlsx', 'csv'])) {
            $fileName = 'payments_audit_' . now()->format('Ymd_His') . '.' . $format;
            return Excel::download(new PaymentsAuditExport($query), $fileName);
        }

        if ($format === 'pdf') {
            $rows = $query->get();
            $pdf = Pdf::loadView('admin.reports.payments.pdf', [
                'rows' => $rows,
                'filters' => $filters,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('CEAF_Payments_Report_' . now()->format('Ymd_His') . '.pdf');
        }

        abort(400, 'Invalid export format.');
    }

private function buildQuery(array $filters)
{
    return Payment::query()
        ->with(['member.user', 'paymentCycle'])

        ->when($filters['year'], function ($q) use ($filters) {
            $q->whereHas('paymentCycle', fn($c) => $c->where('year', $filters['year']));
        })

        ->when($filters['cycle_type'], function ($q) use ($filters) {
            $q->whereHas('paymentCycle', fn($c) => $c->where('type', $filters['cycle_type']));
        })

        ->when($filters['status'], function ($q) use ($filters) {
            // case-insensitive safety
            $q->whereRaw('LOWER(status) = ?', [strtolower($filters['status'])]);
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

        // Date filtering: paid_at for paid, created_at for unpaid
        ->when($filters['from'], function ($q) use ($filters) {
            $from = $filters['from'];
            $q->where(function ($q) use ($from) {
                $q->whereDate('paid_at', '>=', $from)
                  ->orWhere(function ($q) use ($from) {
                      $q->whereNull('paid_at')
                        ->whereDate('created_at', '>=', $from);
                  });
            });
        })
        ->when($filters['to'], function ($q) use ($filters) {
            $to = $filters['to'];
            $q->where(function ($q) use ($to) {
                $q->whereDate('paid_at', '<=', $to)
                  ->orWhere(function ($q) use ($to) {
                      $q->whereNull('paid_at')
                        ->whereDate('created_at', '<=', $to);
                  });
            });
        });
}

    private function filters(Request $request): array
    {
        return [
            'year' => $request->integer('year') ?: null,
            'cycle_type' => $request->string('cycle_type')->toString() ?: null, // seed, replenishment, annual etc
            'status' => $request->string('status')->toString() ?: null,         // paid, pending, late
            'member_search' => trim((string) $request->get('member_search', '')) ?: null,
            'from' => $request->get('from') ?: null, // YYYY-MM-DD
            'to' => $request->get('to') ?: null,
        ];
    }
}

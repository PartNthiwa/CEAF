<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use App\Models\Payment;
use App\Models\Event;

class SeedFundsReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->filters($request);

        $ledger = $this->buildLedger($filters);

        $summary = $this->summary($filters);

        // paginate manually (collection pagination)
        $perPage = 20;
        $page = max((int) $request->get('page', 1), 1);
        $total = $ledger->count();

        $items = $ledger->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('admin.reports.seed_funds.index', [
            'filters' => $filters,
            'summary' => $summary,
            'ledger' => $paginator,
        ]);
    }

    public function export(Request $request)
    {
        $filters = $this->filters($request);
        $format = strtolower($request->get('format', 'pdf'));

        $ledger = $this->buildLedger($filters);
        $summary = $this->summary($filters);

        if (in_array($format, ['xlsx', 'csv'])) {
            // simplest: export from view data using a basic array exporter
            // If you prefer same style as Payments export (FromQuery), tell me and I’ll build that too.
            return $this->exportSpreadsheet($ledger, $filters, $format);
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.seed_funds.pdf', [
                'rows' => $ledger,
                'filters' => $filters,
                'summary' => $summary,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('seed_funds_report_' . now()->format('Ymd_His') . '.pdf');
        }

        abort(400, 'Invalid export format.');
    }

    private function exportSpreadsheet(Collection $ledger, array $filters, string $format)
    {
        // lightweight inline export without creating a new Export class
        // If you want it consistent with the others, I can convert to a Maatwebsite Export class.
        $fileName = 'seed_funds_report_' . now()->format('Ymd_His') . '.' . $format;

        $headings = [
            'Date',
            'Type',
            'Reference',
            'Member',
            'Details',
            'Amount In',
            'Amount Out',
        ];

        $rows = $ledger->map(function ($r) {
            return [
                $r['date'],
                $r['direction'],
                $r['ref'],
                $r['member'],
                $r['details'],
                $r['amount_in'],
                $r['amount_out'],
            ];
        });

        return \Maatwebsite\Excel\Facades\Excel::download(
            new class($headings, $rows) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
                public function __construct(public array $headings, public $rows) {}
                public function array(): array { return $this->rows->toArray(); }
                public function headings(): array { return $this->headings; }
            },
            $fileName
        );
    }

    private function buildLedger(array $filters): Collection
    {
        $year = $filters['year'];

        // ===== IN: Seed payments paid =====
        $in = collect();

        if ($filters['type'] === null || $filters['type'] === 'in') {
            $q = Payment::query()
                ->with(['member.user', 'paymentCycle'])
                ->where('status', 'paid')
                ->whereNotNull('paid_at')
                ->whereHas('paymentCycle', function ($c) use ($year) {
                    $c->where('type', 'seed')->where('year', $year);
                });

            // optional member search
            if ($filters['member_search']) {
                $s = $filters['member_search'];
                $q->whereHas('member.user', function ($u) use ($s) {
                    $u->where(function ($u) use ($s) {
                        $u->where('name', 'like', "%{$s}%")
                          ->orWhere('email', 'like', "%{$s}%");
                    });
                });
            }

            // optional date range (paid_at)
            if ($filters['from']) $q->whereDate('paid_at', '>=', $filters['from']);
            if ($filters['to'])   $q->whereDate('paid_at', '<=', $filters['to']);

            $in = $q->get()->map(function ($p) {
                $memberName = $p->member?->user?->name ?? '—';
                $memberEmail = $p->member?->user?->email ?? '—';

                $paypal = $p->paypal_order_id ?? $p->paypal_payment_id ?? $p->paypal_payment_id ?? '—';

                return [
                    'sort_date' => optional($p->paid_at)->timestamp ?? 0,
                    'date' => optional($p->paid_at)->format('d M Y') ?? '—',
                    'direction' => 'IN',
                    'ref' => 'PAY-' . $p->id,
                    'member' => trim($memberName . ' (' . $memberEmail . ')'),
                    'details' => 'Seed payment • ' . ($paypal !== '—' ? "PayPal: {$paypal}" : 'No PayPal ID'),
                    'amount_in' => number_format((float) ($p->amount_paid ?? 0), 2),
                    'amount_out' => '',
                ];
            });
        }

        // ===== OUT: Events approved and paid from seed =====
        $out = collect();

        if ($filters['type'] === null || $filters['type'] === 'out') {
            $q = Event::query()
                ->with(['member.user', 'person', 'details'])
                ->where('status', 'approved')
                ->where('paid_from_seed', true)
                ->whereNotNull('approved_at')
                ->whereYear('approved_at', $year);

            // optional member search (events member)
            if ($filters['member_search']) {
                $s = $filters['member_search'];
                $q->whereHas('member.user', function ($u) use ($s) {
                    $u->where(function ($u) use ($s) {
                        $u->where('name', 'like', "%{$s}%")
                          ->orWhere('email', 'like', "%{$s}%");
                    });
                });
            }

            // optional date range (approved_at)
            if ($filters['from']) $q->whereDate('approved_at', '>=', $filters['from']);
            if ($filters['to'])   $q->whereDate('approved_at', '<=', $filters['to']);

            $out = $q->get()->map(function ($e) {
                $memberName = $e->member?->user?->name ?? '—';
                $memberEmail = $e->member?->user?->email ?? '—';

                $personName = $e->person?->full_name
                    ?: trim(($e->person?->first_name ?? '') . ' ' . ($e->person?->last_name ?? ''))
                    ?: '—';

                $title = $e->details?->title ?? 'Event';
                $when = optional($e->details?->event_date)->format('d M Y');

                return [
                    'sort_date' => optional($e->approved_at)->timestamp ?? 0,
                    'date' => optional($e->approved_at)->format('d M Y') ?? '—',
                    'direction' => 'OUT',
                    'ref' => 'EVT-' . $e->id,
                    'member' => trim($memberName . ' (' . $memberEmail . ')'),
                    'details' => trim("{$title} • Person: {$personName}" . ($when ? " • Date: {$when}" : '')),
                    'amount_in' => '',
                    'amount_out' => number_format((float) ($e->approved_amount ?? 0), 2),
                ];
            });
        }

        return $in
            ->concat($out)
            ->sortByDesc('sort_date')
            ->values();
    }

    private function summary(array $filters): array
    {
        $year = $filters['year'];

        $collected = Payment::query()
            ->where('status', 'paid')
            ->whereHas('paymentCycle', fn($c) => $c->where('type', 'seed')->where('year', $year))
            ->sum('amount_paid');

        $spent = Event::query()
            ->whereYear('approved_at', $year)
            ->where('status', 'approved')
            ->where('paid_from_seed', true)
            ->sum('approved_amount');

        return [
            'year' => $year,
            'collected' => (float) $collected,
            'spent' => (float) $spent,
            'balance' => (float) $collected - (float) $spent,
        ];
    }

    private function filters(Request $request): array
    {
        $year = $request->integer('year') ?: (int) now()->format('Y');

        return [
            'year' => $year,
            'type' => $request->get('type') ?: null, // in | out | null(all)
            'member_search' => trim((string) $request->get('member_search', '')) ?: null,
            'from' => $request->get('from') ?: null, // YYYY-MM-DD
            'to' => $request->get('to') ?: null,
        ];
    }
}

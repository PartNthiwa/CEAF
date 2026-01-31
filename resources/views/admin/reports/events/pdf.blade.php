<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Events Audit Report</title>

    <style>
        @page { margin: 90px 40px 70px 40px; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            position: relative;
        }

        /* ===== WATERMARK ===== */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.15;
            z-index: -1000;
        }

        /* ===== HEADER ===== */
        header {
            position: fixed;
            top: -70px;
            left: 0;
            right: 0;
            height: 60px;
            background: #0f766e;
            color: white;
            padding: 10px 15px;
        }

        header table { width: 100%; border-collapse: collapse; }

        .logo { height: 40px; }
        .title { font-size: 18px; font-weight: bold; }
        .right { text-align: right; }

        /* ===== FOOTER ===== */
        footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 40px;
            text-align: center;
            font-size: 10px;
            background: #f1f5f9;
            padding-top: 6px;
        }

        .confidential {
            color: red;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* ===== TABLE ===== */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #ccfbf1; font-weight: bold; }

        .muted { color: #666; font-size: 10px; }
    </style>
</head>
<body>

<img class="watermark" src="{{ public_path('images/ceaflogo.png') }}">

<header>
    <table>
        <tr>
            <td><img class="logo" src="{{ public_path('images/ceaflogo.png') }}"></td>
            <td class="right">
                <div class="title">Events Audit Report</div>
                <div style="font-size:10px;">Report Date: {{ now()->format('d M Y H:i') }}</div>
            </td>
        </tr>
    </table>
</header>

<footer>
    © {{ date('Y') }} Carolina East Africa Foundation –
    <span class="confidential">CONFIDENTIAL</span><br>

    Search CR -
    {{
        collect($filters)->filter()->map(function ($v, $k) {
            if (is_bool($v)) return $k . ': ' . ($v ? 'Yes' : 'No');
            return $k . ': ' . $v;
        })->join(' | ') ?: 'None'
    }}
</footer>

<main>
    <table>
        <thead>
        <tr>
            <th>Member</th>
            <th>Person</th>
            <th>Title</th>
            <th>Event Date</th>
            <th>Location</th>
            <th>Status</th>
            <th>Approved At</th>
            <th>Approved Amount</th>
            <th>Seed?</th>
            <th>Replenish?</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $e)
            @php
                $personName = $e->person?->full_name
                    ?: trim(($e->person?->first_name ?? '') . ' ' . ($e->person?->last_name ?? ''))
                    ?: '—';
                $personContact = $e->person?->contact ?? '—';
            @endphp

            <tr>
                <td>
                    {{ $e->member?->user?->name ?? '—' }}<br>
                    <span class="muted">{{ $e->member?->user?->email ?? '—' }}</span>
                </td>

                <td>
                    {{ $personName }}<br>
                    <span class="muted">{{ $personContact }}</span>
                </td>

                <td>{{ $e->details?->title ?? '—' }}</td>
                <td>{{ optional($e->details?->event_date)?->format('d M Y') ?? '—' }}</td>
                <td>{{ $e->details?->location ?? '—' }}</td>

                <td>{{ ucfirst($e->status ?? '—') }}</td>
                <td>{{ optional($e->approved_at)?->format('d M Y') ?? '—' }}</td>
                <td>{{ number_format((float)($e->approved_amount ?? 0), 2) }}</td>

                <td>{{ ($e->paid_from_seed ?? 0) ? 'Yes' : 'No' }}</td>
                <td>{{ ($e->requires_replenishment ?? 0) ? 'Yes' : 'No' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</main>

<script type="text/php">
if (isset($pdf)) {
    $font = $fontMetrics->getFont("DejaVu Sans");
    $size = 9;
    $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
    $x = 430;
    $y = 800;
    $pdf->page_text($x, $y, $text, $font, $size);
}
</script>

</body>
</html>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Seed Funds Report</title>

    <style>
        @page { margin: 90px 40px 70px 40px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; position: relative; }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.15;
            z-index: -1000;
        }

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

        .confidential { color: red; font-weight: bold; letter-spacing: 1px; }
        .muted { color: #666; font-size: 10px; }

        .summary {
            margin-top: 10px;
            border: 1px solid #ddd;
            padding: 8px;
            background: #f8fafc;
        }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #ccfbf1; font-weight: bold; }
    </style>
</head>
<body>

<img class="watermark" src="{{ public_path('images/ceaflogo.png') }}">

<header>
    <table>
        <tr>
            <td><img class="logo" src="{{ public_path('images/ceaflogo.png') }}"></td>
            <td class="right">
                <div class="title">Seed Funds Report</div>
                <div style="font-size:10px;">Report Date: {{ now()->format('d M Y H:i') }}</div>
            </td>
        </tr>
    </table>
</header>

<footer>
    © {{ date('Y') }} Carolina East Africa Foundation –
    <span class="confidential">CONFIDENTIAL</span><br>
    Search CR - {{ collect($filters)->filter()->map(fn($v,$k)=>"$k: $v")->join(' | ') ?: 'None' }}
</footer>

<main>
    <div class="summary">
        <strong>Year:</strong> {{ $summary['year'] }} |
        <strong>Collected:</strong> ${{ number_format($summary['collected'], 2) }} |
        <strong>Spent:</strong> ${{ number_format($summary['spent'], 2) }} |
        <strong>Balance:</strong> ${{ number_format($summary['balance'], 2) }}
    </div>

    <table>
        <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Reference</th>
            <th>Member</th>
            <th>Details</th>
            <th>Amount In</th>
            <th>Amount Out</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $r)
            <tr>
                <td>{{ $r['date'] }}</td>
                <td>{{ $r['direction'] }}</td>
                <td>{{ $r['ref'] }}</td>
                <td>{{ $r['member'] }}</td>
                <td>{{ $r['details'] }}</td>
                <td>{{ $r['amount_in'] ? '$'.$r['amount_in'] : '' }}</td>
                <td>{{ $r['amount_out'] ? '$'.$r['amount_out'] : '' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</main>

<script type="text/php">
if (isset($pdf)) {
    $font = $fontMetrics->getFont("DejaVu Sans");
    $size = 9;
    $pdf->page_text(430, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, $size);
}
</script>

</body>
</html>

@extends('layouts.admin')

@section('content')
<div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6">

    {{-- Header + Navigation --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div class="min-w-0">
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Reports</span> &gt;
                <span class="font-semibold text-gray-900">Payments</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">
                Payments Audit Report
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Filter and export payments as Excel, CSV, or PDF.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
            <button
                type="button"
                onclick="window.history.back()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                       bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold
                       hover:bg-gray-200 transition"
            >
                ← Back
            </button>

            <a
                href="{{ route('admin.dashboard') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                       bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-lg font-semibold
                       hover:bg-gray-50 transition"
            >
                Dashboard
            </a>
        </div>
    </div>

    {{-- Flash messages --}}
    @if (session()->has('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @php $qs = request()->query(); @endphp

    {{-- Filters + Export --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                <p class="text-sm text-gray-500">Use filters below, then export with the same criteria.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                <a href="{{ url()->current() }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold text-center">
                    Reset Filters
                </a>

                <a href="{{ route('admin.reports.payments.export', array_merge($qs, ['format' => 'xlsx'])) }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-semibold text-center">
                    Download Excel
                </a>

                <a href="{{ route('admin.reports.payments.export', array_merge($qs, ['format' => 'csv'])) }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-semibold text-center">
                    Download CSV
                </a>

                <a href="{{ route('admin.reports.payments.export', array_merge($qs, ['format' => 'pdf'])) }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black font-semibold text-center">
                    Download PDF
                </a>
            </div>
        </div>

        <form method="GET" class="p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Year</label>
                    <input name="year" type="number" value="{{ request('year') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="2026">
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-semibold text-gray-600">Cycle Type</label>
                    <select name="cycle_type"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All</option>
                        <option value="seed" @selected(request('cycle_type')==='seed')>Seed</option>
                        <option value="replenishment" @selected(request('cycle_type')==='replenishment')>Replenishment</option>
                        <option value="annual" @selected(request('cycle_type')==='annual')>Annual</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Status</label>
                    <select name="status"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All</option>
                        <option value="paid" @selected(request('status')==='paid')>Paid</option>
                        <option value="pending" @selected(request('status')==='pending')>Pending</option>
                        <option value="late" @selected(request('status')==='late')>Late</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-semibold text-gray-600">Member (name/email)</label>
                    <input name="member_search" type="text" value="{{ request('member_search') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Search member...">
                </div>

                <div class="md:col-span-2 grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-600">From</label>
                        <input name="from" type="date" value="{{ request('from') }}"
                               class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600">To</label>
                        <input name="to" type="date" value="{{ request('to') }}"
                               class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="md:col-span-12 flex justify-end pt-2">
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-semibold">
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>


    @php
        $active = collect(request()->only([
            'year','cycle_type','status','member_search','from','to'
        ]))->filter(fn($v) => filled($v));

        $hasFilters = $active->isNotEmpty();

        $labels = [
            'year' => 'Year',
            'cycle_type' => 'Cycle',
            'status' => 'Status',
            'member_search' => 'Member',
            'from' => 'From',
            'to' => 'To',
        ];
    @endphp

@if($hasFilters)
    <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-4 sm:p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-2">
               <span id="filteringBadge"
                class="hidden inline-flex items-center gap-2 px-2 py-1 rounded-md text-xs font-semibold bg-indigo-600 text-white">
                <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="white" stroke-width="4" fill="none" opacity="0.3"></circle>
                    <path d="M22 12a10 10 0 0 1-10 10" stroke="white" stroke-width="4" fill="none"></path>
                </svg>
                Filtering...
            </span>

            <span id="filteredBadge"
                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                Filtered
            </span>

                <p class="text-sm text-indigo-900 font-semibold">
                    Showing results with {{ $active->count() }} active filter{{ $active->count() > 1 ? 's' : '' }}.
                </p>
            </div>

            <a href="{{ url()->current() }}"
               class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-white border border-indigo-200 text-indigo-700 font-semibold hover:bg-indigo-100 transition">
                Clear all filters
            </a>
        </div>

        <div class="mt-3 flex flex-wrap gap-2">
            @foreach($active as $key => $value)
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white border border-indigo-200 text-indigo-800">
                    {{ $labels[$key] ?? ucfirst($key) }}:
                    <span class="font-bold">
                        {{ $key === 'cycle_type' ? ucfirst($value) : ($key === 'status' ? ucfirst($value) : $value) }}
                    </span>

                    {{-- remove single filter --}}
                    <a class="ml-1 text-indigo-500 hover:text-indigo-800"
                       href="{{ url()->current() . '?' . http_build_query(collect(request()->query())->except($key)->toArray()) }}">
                        ✕
                    </a>
                </span>
            @endforeach
        </div>
    </div>
@endif

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left font-medium">Member</th>
                    <th class="px-6 py-3 text-left font-medium">Cycle</th>
                    <th class="px-6 py-3 text-left font-medium">Amount Due</th>
                    <th class="px-6 py-3 text-left font-medium">Late Fee</th>
                    <th class="px-6 py-3 text-left font-medium">Status</th>
                    <th class="px-6 py-3 text-left font-medium">Paid At</th>
                     <th class="px-6 py-3 text-left font-medium">Amount Paid</th>
                    <th class="px-6 py-3 text-left font-medium">PayPal ID</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-800">
                @forelse($payments as $payment)
                    @php
                        $memberName = $payment->member->user->name ?? '—';
                        $memberEmail = $payment->member->user->email ?? '—';
                        $cycleType  = ucfirst($payment->paymentCycle->type ?? '—');
                        $cycleYear  = $payment->paymentCycle->year ?? '—';
                        $amount = (float) ($payment->amount_due ?? 0);
                        $lateFee = (float) ($payment->late_fee ?? 0);
                        $status = strtolower($payment->status ?? 'unknown');

                        $statusPill = match($status) {
                            'paid' => 'bg-green-50 text-green-700 ring-green-600/20',
                            'pending' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20',
                            'late' => 'bg-red-50 text-red-700 ring-red-600/20',
                            default => 'bg-gray-50 text-gray-600 ring-gray-500/20',
                        };

                        $paidAt = $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') : '—';
                        $amountPaid = (float) ($payment->amount_paid ?? 0);
                        $paypalId = $payment->paypal_order_id ?? $payment->paypal_payment_id ?? '—';
                    @endphp

                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <div class="font-medium text-gray-900">{{ $memberName }}</div>
                            <div class="text-xs text-gray-500 break-all">{{ $memberEmail }}</div>
                        </td>
                        <td class="px-6 py-3 text-gray-700">{{ $cycleType }} {{ $cycleYear }}</td>
                        <td class="px-6 py-3 font-semibold">${{ number_format($amount, 2) }}</td>
                        <td class="px-6 py-3">${{ number_format($lateFee, 2) }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md ring-1 ring-inset {{ $statusPill }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-gray-700">{{ $paidAt }}</td>
                            <td class="px-6 py-3 font-semibold">${{ number_format($amountPaid, 2) }}</td>
                        <td class="px-6 py-3 text-xs text-gray-600"><span class="break-all">{{ $paypalId }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">No payments found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 sm:p-6 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden divide-y">
        @forelse($payments as $payment)
            @php
                $memberName = $payment->member->user->name ?? '—';
                $memberEmail = $payment->member->user->email ?? '—';
                $cycleType  = ucfirst($payment->paymentCycle->type ?? '—');
                $cycleYear  = $payment->paymentCycle->year ?? '—';
                $amount = (float) ($payment->amount_due ?? 0);
                $lateFee = (float) ($payment->late_fee ?? 0);
                $status = strtolower($payment->status ?? 'unknown');

                $statusPill = match($status) {
                    'paid' => 'bg-green-50 text-green-700',
                    'pending' => 'bg-yellow-50 text-yellow-800',
                    'late' => 'bg-red-50 text-red-700',
                    default => 'bg-gray-50 text-gray-600',
                };

                $paidAt = $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') : '—';
                 $amountPaid = (float) ($payment->amount_paid ?? 0);
                $paypalId = $payment->paypal_order_id ?? $payment->paypal_payment_id ?? '—';
            @endphp

            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 break-words">{{ $memberName }}</p>
                        <p class="text-xs text-gray-500 break-all">{{ $memberEmail }}</p>
                        <p class="text-sm text-gray-600 mt-1">Cycle: {{ $cycleType }} {{ $cycleYear }}</p>
                    </div>

                    <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold {{ $statusPill }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>

                <div class="text-sm text-gray-700 space-y-1">
                    <div><span class="text-gray-500">Amount Due:</span> <span class="font-semibold">${{ number_format($amount, 2) }}</span></div>
                    <div><span class="text-gray-500">Late Fee:</span> ${{ number_format($lateFee, 2) }}</div>
                    <div><span class="text-gray-500">Paid At:</span> {{ $paidAt }}</div>
                      <div><span class="text-gray-500">Amount Paid:</span> <span class="font-semibold">${{ number_format($amountPaid, 2) }}</span></div>

                    <div class="text-xs text-gray-600"><span class="text-gray-500">PayPal ID:</span> <span class="break-all">{{ $paypalId }}</span></div>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-500">No payments found.</div>
        @endforelse

        <div class="p-4 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    </div>

</div>


 <script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form[method="GET"]');
  const filtering = document.getElementById('filteringBadge');
  const filtered = document.getElementById('filteredBadge');

  if (!form || !filtering || !filtered) return;

  const inputs = form.querySelectorAll('input, select');

  inputs.forEach(el => {
    el.addEventListener('input', () => {
      filtering.classList.remove('hidden');
      filtered.classList.add('hidden');
    });
    el.addEventListener('change', () => {
      filtering.classList.remove('hidden');
      filtered.classList.add('hidden');
    });
  });
});
</script>


@endsection

@extends('layouts.admin')

@section('content')
@php $qs = request()->query(); @endphp

<div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div class="min-w-0">
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Reports</span> &gt;
                <span class="font-semibold text-gray-900">Seed Funds</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">
                Seed Funds Report
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Track seed collections, event spending, and balances.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
            <a href="{{ url()->current() }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-200 transition">
                Reset Filters
            </a>
        </div>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="text-sm text-gray-500">Collected ({{ $summary['year'] }})</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($summary['collected'], 2) }}</div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="text-sm text-gray-500">Spent ({{ $summary['year'] }})</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($summary['spent'], 2) }}</div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="text-sm text-gray-500">Balance</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($summary['balance'], 2) }}</div>
        </div>
    </div>

    {{-- Filters + Export --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                <p class="text-sm text-gray-500">Filter the ledger and export with same criteria.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                <a href="{{ route('admin.reports.seed_funds.export', array_merge($qs, ['format' => 'xlsx'])) }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-semibold text-center">
                    Download Excel
                </a>

                <a href="{{ route('admin.reports.seed_funds.export', array_merge($qs, ['format' => 'csv'])) }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-semibold text-center">
                    Download CSV
                </a>

                <a href="{{ route('admin.reports.seed_funds.export', array_merge($qs, ['format' => 'pdf'])) }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black font-semibold text-center">
                    Download PDF
                </a>
            </div>
        </div>

        <form method="GET" class="p-4 sm:p-6" id="seedFundsFilterForm">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Year</label>
                    <input name="year" type="number" value="{{ request('year', now()->format('Y')) }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Type</label>
                    <select name="type"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" @selected(request('type')==='')>All</option>
                        <option value="in" @selected(request('type')==='in')>IN (Collections)</option>
                        <option value="out" @selected(request('type')==='out')>OUT (Events)</option>
                    </select>
                </div>

                <div class="md:col-span-4">
                    <label class="text-xs font-semibold text-gray-600">Member (name/email)</label>
                    <input name="member_search" type="text" value="{{ request('member_search') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Search member...">
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">From</label>
                    <input name="from" type="date" value="{{ request('from') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">To</label>
                    <input name="to" type="date" value="{{ request('to') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="md:col-span-12 flex justify-end pt-2">
                    <button type="submit" id="applyBtn"
                            class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-semibold">
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Active filter chips --}}
    @php
        $active = collect(request()->only(['year','type','member_search','from','to']))
            ->filter(fn($v) => filled($v));
        $labels = [
            'year' => 'Year',
            'type' => 'Type',
            'member_search' => 'Member',
            'from' => 'From',
            'to' => 'To',
        ];
    @endphp

    @if($active->isNotEmpty())
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-4 sm:p-5">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-2">
                    <span id="seedFilteringBadge"
                          class="hidden inline-flex items-center gap-2 px-3 py-1 rounded-md text-xs font-semibold bg-indigo-600 text-white">
                        <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="white" stroke-width="4" fill="none" opacity="0.3"></circle>
                            <path d="M22 12a10 10 0 0 1-10 10" stroke="white" stroke-width="4" fill="none"></path>
                        </svg>
                        Filtering...
                    </span>

                    <span id="seedFilteredBadge"
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
                        <span class="font-bold">{{ $value }}</span>

                        <a class="ml-1 text-indigo-500 hover:text-indigo-800"
                           href="{{ url()->current() . '?' . http_build_query(collect(request()->query())->except($key)->toArray()) }}">
                            ✕
                        </a>
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Ledger Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left font-medium">Date</th>
                    <th class="px-6 py-3 text-left font-medium">Type</th>
                    <th class="px-6 py-3 text-left font-medium">Reference</th>
                    <th class="px-6 py-3 text-left font-medium">Member</th>
                    <th class="px-6 py-3 text-left font-medium">Details</th>
                    <th class="px-6 py-3 text-left font-medium">Amount In</th>
                    <th class="px-6 py-3 text-left font-medium">Amount Out</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-800">
                @forelse($ledger as $r)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">{{ $r['date'] }}</td>
                        <td class="px-6 py-3 font-semibold">{{ $r['direction'] }}</td>
                        <td class="px-6 py-3">{{ $r['ref'] }}</td>
                        <td class="px-6 py-3">{{ $r['member'] }}</td>
                        <td class="px-6 py-3">{{ $r['details'] }}</td>
                        <td class="px-6 py-3 font-semibold">{{ $r['amount_in'] ? '$'.$r['amount_in'] : '' }}</td>
                        <td class="px-6 py-3 font-semibold">{{ $r['amount_out'] ? '$'.$r['amount_out'] : '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">No ledger entries found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 sm:p-6 border-t border-gray-100">
            {{ $ledger->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('seedFundsFilterForm');
  const filtering = document.getElementById('seedFilteringBadge');
  const filtered = document.getElementById('seedFilteredBadge');
  const btn = document.getElementById('applyBtn');

  if (!form || !filtering || !filtered) return;

  form.addEventListener('submit', () => {
    filtering.classList.remove('hidden');
    filtered.classList.add('hidden');
    if (btn) {
      btn.disabled = true;
      btn.textContent = 'Applying...';
      btn.classList.add('opacity-70','cursor-not-allowed');
    }
  });

  window.addEventListener('pageshow', () => {
    filtering.classList.add('hidden');
    filtered.classList.remove('hidden');
    if (btn) btn.disabled = false;
  });
});
</script>
@endsection

@extends('layouts.admin')

@section('content')
@php $qs = request()->query(); @endphp

<div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div class="min-w-0">
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Reports</span> &gt;
                <span class="font-semibold text-gray-900">Beneficiaries</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">
                Beneficiaries Audit Report
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Filter and export beneficiaries as Excel, CSV, or PDF.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
            <button type="button" onclick="window.history.back()"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-200 transition">
                ← Back
            </button>

            <a href="{{ route('admin.dashboard') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition">
                Dashboard
            </a>
        </div>
    </div>

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

                <a href="{{ route('admin.reports.beneficiaries.export', array_merge($qs, ['format' => 'xlsx'])) }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-semibold text-center">
                    Download Excel
                </a>

                <a href="{{ route('admin.reports.beneficiaries.export', array_merge($qs, ['format' => 'csv'])) }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-semibold text-center">
                    Download CSV
                </a>

                <a href="{{ route('admin.reports.beneficiaries.export', array_merge($qs, ['format' => 'pdf'])) }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black font-semibold text-center">
                    Download PDF
                </a>
            </div>
        </div>

        <form method="GET" class="p-4 sm:p-6" id="beneficiariesFilterForm">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Member Year (Join Date)</label>
                    <input name="member_year" type="number" value="{{ request('member_year') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="2026">
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-semibold text-gray-600">Member (name/email)</label>
                    <input name="member_search" type="text" value="{{ request('member_search') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Search member...">
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-semibold text-gray-600">Beneficiary (name/contact)</label>
                    <input name="beneficiary_search" type="text" value="{{ request('beneficiary_search') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Search beneficiary...">
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Person (first/last/contact)</label>
                    <input name="person_search" type="text" value="{{ request('person_search') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Search linked person...">
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Has Linked Person</label>
                    <select name="has_person"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" @selected(request('has_person')==='')>All</option>
                        <option value="1" @selected(request('has_person')==='1')>Yes</option>
                        <option value="0" @selected(request('has_person')==='0')>No</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Min %</label>
                    <input name="pct_min" type="number" step="0.01" value="{{ request('pct_min') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="0">
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Max %</label>
                    <input name="pct_max" type="number" step="0.01" value="{{ request('pct_max') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="100">
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

    {{-- Active filter chips + reactive badge --}}
    @php
        $active = collect(request()->only([
            'member_year','member_search','beneficiary_search','person_search','has_person','pct_min','pct_max'
        ]))->filter(function ($v) {
            return filled($v) || $v === '0';
        });

        $hasFilters = $active->isNotEmpty();

        $labels = [
            'member_year' => 'Member Year',
            'member_search' => 'Member',
            'beneficiary_search' => 'Beneficiary',
            'person_search' => 'Person',
            'has_person' => 'Has Person',
            'pct_min' => 'Min %',
            'pct_max' => 'Max %',
        ];
    @endphp

    @if($hasFilters)
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span id="benefFilteringBadge"
                          class="hidden inline-flex items-center gap-2 px-3 py-1 rounded-md text-xs font-semibold bg-indigo-600 text-white">
                        <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="white" stroke-width="4" fill="none" opacity="0.3"></circle>
                            <path d="M22 12a10 10 0 0 1-10 10" stroke="white" stroke-width="4" fill="none"></path>
                        </svg>
                        Filtering...
                    </span>

                    <span id="benefFilteredBadge"
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
                            @if($key === 'has_person')
                                {{ $value === '1' ? 'Yes' : 'No' }}
                            @else
                                {{ $value }}
                            @endif
                        </span>

                        <a class="ml-1 text-indigo-500 hover:text-indigo-800"
                           href="{{ url()->current() . '?' . http_build_query(collect(request()->query())->except($key)->toArray()) }}">
                            ✕
                        </a>
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Summary --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-600">
            Showing <span class="font-semibold">{{ $filteredCount ?? $beneficiaries->total() }}</span>
            of <span class="font-semibold">{{ $totalCount ?? $beneficiaries->total() }}</span> beneficiaries
        </p>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left font-medium">Member</th>
                    <th class="px-6 py-3 text-left font-medium">Beneficiary</th>
                    <th class="px-6 py-3 text-left font-medium">Contact</th>
                    <th class="px-6 py-3 text-left font-medium">Percentage</th>
                    <th class="px-6 py-3 text-left font-medium">Linked Person</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-800">
                @forelse($beneficiaries as $b)
                    @php
                        $personName = $b->person?->full_name
                            ?: trim(($b->person?->first_name ?? '') . ' ' . ($b->person?->last_name ?? ''))
                            ?: '—';
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <div class="font-medium text-gray-900">{{ $b->member?->user?->name ?? '—' }}</div>
                            <div class="text-xs text-gray-500 break-all">{{ $b->member?->user?->email ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-3">{{ $b->name ?? '—' }}</td>
                        <td class="px-6 py-3">{{ $b->contact ?? '—' }}</td>
                        <td class="px-6 py-3 font-semibold">{{ number_format((float)($b->percentage ?? 0), 2) }}%</td>
                        <td class="px-6 py-3">
                            <div class="font-medium text-gray-900">{{ $personName }}</div>
                            <div class="text-xs text-gray-500">{{ $b->person?->contact ?? '—' }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">No beneficiaries found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 sm:p-6 border-t border-gray-100">
            {{ $beneficiaries->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('beneficiariesFilterForm');
  const filtering = document.getElementById('benefFilteringBadge');
  const filtered = document.getElementById('benefFilteredBadge');
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

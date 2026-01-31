@extends('layouts.admin')

@section('content')
@php $qs = request()->query(); @endphp

<div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div class="min-w-0">
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Reports</span> &gt;
                <span class="font-semibold text-gray-900">Dependents</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">
                Dependents Audit Report
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Filter and export dependents as Excel, CSV, or PDF.
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

                <a href="{{ route('admin.reports.dependents.export', array_merge($qs, ['format' => 'xlsx'])) }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-semibold text-center">
                    Download Excel
                </a>

                <a href="{{ route('admin.reports.dependents.export', array_merge($qs, ['format' => 'csv'])) }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-semibold text-center">
                    Download CSV
                </a>

                <a href="{{ route('admin.reports.dependents.export', array_merge($qs, ['format' => 'pdf'])) }}"
                   class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-black font-semibold text-center">
                    Download PDF
                </a>
            </div>
        </div>

        <form method="GET" class="p-4 sm:p-6" id="dependentsFilterForm">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Status</label>
                    <select name="status"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All</option>
                        <option value="active" @selected(request('status')==='active')>Active</option>
                        <option value="deceased" @selected(request('status')==='deceased')>Deceased</option>
                        <option value="inactive" @selected(request('status')==='inactive')>Inactive</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-semibold text-gray-600">Relationship</label>
                    <input name="relationship" type="text" value="{{ request('relationship') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Spouse, Child...">
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Profile Completed</label>
                    <select name="profile_completed"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" @selected(request('profile_completed')==='')>All</option>
                        <option value="1" @selected(request('profile_completed')==='1')>Yes</option>
                        <option value="0" @selected(request('profile_completed')==='0')>No</option>
                    </select>
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

                <div class="md:col-span-3">
                    <label class="text-xs font-semibold text-gray-600">Member (name/email)</label>
                    <input name="member_search" type="text" value="{{ request('member_search') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Search member...">
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-semibold text-gray-600">Dependent Name</label>
                    <input name="dependent_search" type="text" value="{{ request('dependent_search') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Search dependent...">
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-semibold text-gray-600">Linked Person (first/last/contact)</label>
                    <input name="person_search" type="text" value="{{ request('person_search') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Search linked person...">
                </div>

                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Member Year (Join Date)</label>
                    <input name="member_year" type="number" value="{{ request('member_year') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="2026">
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
            'status','relationship','profile_completed','has_person','member_search','dependent_search','person_search','member_year'
        ]))->filter(fn($v) => filled($v) || $v === '0');

        $labels = [
            'status' => 'Status',
            'relationship' => 'Relationship',
            'profile_completed' => 'Profile',
            'has_person' => 'Has Person',
            'member_search' => 'Member',
            'dependent_search' => 'Dependent',
            'person_search' => 'Person',
            'member_year' => 'Member Year',
        ];
    @endphp

    @if($active->isNotEmpty())
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span id="depFilteringBadge"
                          class="hidden inline-flex items-center gap-2 px-3 py-1 rounded-md text-xs font-semibold bg-indigo-600 text-white">
                        <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="white" stroke-width="4" fill="none" opacity="0.3"></circle>
                            <path d="M22 12a10 10 0 0 1-10 10" stroke="white" stroke-width="4" fill="none"></path>
                        </svg>
                        Filtering...
                    </span>

                    <span id="depFilteredBadge"
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
                            @if(in_array($key, ['profile_completed','has_person']))
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
            Showing <span class="font-semibold">{{ $filteredCount ?? $dependents->total() }}</span>
            of <span class="font-semibold">{{ $totalCount ?? $dependents->total() }}</span> dependents
        </p>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left font-medium">Member</th>
                    <th class="px-6 py-3 text-left font-medium">Dependent</th>
                    <th class="px-6 py-3 text-left font-medium">Relationship</th>
                    <th class="px-6 py-3 text-left font-medium">Status</th>
                    <th class="px-6 py-3 text-left font-medium">Profile</th>
                    <th class="px-6 py-3 text-left font-medium">Linked Person</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-800">
                @forelse($dependents as $d)
                    @php
                        $personName = $d->person?->full_name
                            ?: trim(($d->person?->first_name ?? '') . ' ' . ($d->person?->last_name ?? ''))
                            ?: '—';
                    @endphp

                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <div class="font-medium text-gray-900">{{ $d->member?->user?->name ?? '—' }}</div>
                            <div class="text-xs text-gray-500 break-all">{{ $d->member?->user?->email ?? '—' }}</div>
                        </td>

                        <td class="px-6 py-3">{{ $d->name ?? '—' }}</td>
                        <td class="px-6 py-3">{{ $d->relationship ?? '—' }}</td>
                        <td class="px-6 py-3">{{ ucfirst($d->status ?? '—') }}</td>
                        <td class="px-6 py-3">{{ ($d->profile_completed ?? 0) ? 'Yes' : 'No' }}</td>

                        <td class="px-6 py-3">
                            <div class="font-medium text-gray-900">{{ $personName }}</div>
                            <div class="text-xs text-gray-500">{{ $d->person?->contact ?? '—' }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">No dependents found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 sm:p-6 border-t border-gray-100">
            {{ $dependents->links() }}
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('dependentsFilterForm');
  const filtering = document.getElementById('depFilteringBadge');
  const filtered = document.getElementById('depFilteredBadge');
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

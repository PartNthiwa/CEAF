<div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6">

    {{-- Header + Navigation --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Pending Members</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Pending Members</h1>
            <p class="text-sm text-gray-600 mt-1">
                Review newly registered members and approve access.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2">
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

            <a
                href="{{ route('admin.members-list') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                       bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-lg font-semibold
                       hover:bg-gray-50 transition"
            >
                Members
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session()->has('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="p-4 sm:p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Pending Approvals</h2>
                <p class="text-sm text-gray-600 mt-1">Approve members to activate their accounts.</p>
            </div>
            <div class="text-sm text-gray-500">
                {{ $members->count() }} total
            </div>
        </div>

        <div class="divide-y">
            @forelse($members as $member)
                @php
                    $name = $member->user->name ?? '—';
                    $email = $member->user->email ?? '—';
                    $registered = $member->created_at ? $member->created_at->diffForHumans() : '—';
                @endphp

                <div class="p-4 sm:p-6 hover:bg-gray-50 transition">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div class="flex items-start gap-4 min-w-0">
                            {{-- Avatar --}}
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold shrink-0">
                                {{ strtoupper(substr($name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="font-semibold text-gray-900 break-words">
                                        {{ $name }}
                                    </div>

                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold
                                                 bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        Pending
                                    </span>
                                </div>

                                <div class="text-sm text-gray-600 break-all mt-1">{{ $email }}</div>

                                <div class="text-xs text-gray-400 mt-1">
                                    Registered {{ $registered }}
                                </div>
                            </div>
                        </div>

                        <button
                            type="button"
                            wire:click="approve({{ $member->id }})"
                            wire:loading.attr="disabled"
                            wire:target="approve({{ $member->id }})"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                                   bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold
                                   hover:bg-blue-700 transition disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove wire:target="approve({{ $member->id }})">Approve</span>
                            <span wire:loading wire:target="approve({{ $member->id }})">Approving...</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-gray-500">
                    <div class="text-4xl mb-2">✅</div>
                    <p class="font-medium text-gray-700">No pending members.</p>
                    <p class="text-sm text-gray-400 mt-1">All registrations have been reviewed.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="text-xs text-gray-500 pt-2">
        &copy; {{ now()->year }} Carolina East Africa Foundation
    </div>
</div>

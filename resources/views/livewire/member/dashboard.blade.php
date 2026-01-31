<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-2">
        @if(auth()->user()->last_login_at)
            <p class="text-xs text-gray-500">
                Last login: {{ auth()->user()->last_login_at->diffForHumans() }}
            </p>
        @endif

        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div class="space-y-1">
                <p class="text-sm text-gray-600">
                    Welcome, <span class="font-semibold text-gray-900">{{ auth()->user()->name ?? 'Member' }}</span>
                </p>

                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    Carolina East Africa Foundation — Bereavement Registry
                </h1>

                <p class="text-sm text-gray-600">
                    View your membership status, manage dependents/beneficiaries, and see approved events.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a
                    href="{{ route('member.payments') }}"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold
                           transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Make Payment
                </a>

                {{-- Optional --}}
                {{-- <a href="{{ route('member.profile') }}"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-white hover:bg-gray-50 text-gray-800 font-semibold border
                           transition focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                >
                    My Profile
                </a> --}}
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    <div class="space-y-3">
        @if($membershipStatus === 'suspended')
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                <div class="flex items-start gap-3">
                    <div class="text-lg leading-none">⚠️</div>
                    <div>
                        <p class="font-semibold">Your membership is suspended.</p>
                        <p class="text-sm mt-1">
                            Please contact support or clear outstanding requirements to restore access.
                        </p>
                    </div>
                </div>
            </div>
        @elseif($amountDue > 0)
            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4 text-indigo-900 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="text-lg leading-none">💳</div>
                    <div>
                        <p class="font-semibold">Payment required</p>
                        <p class="text-sm mt-1 text-indigo-800">
                            Amount due: <span class="font-bold">KES {{ number_format($amountDue) }}</span>
                            @if($nextDeadline)
                                — deadline:
                                <span class="font-semibold">
                                    {{ \Carbon\Carbon::parse($nextDeadline)->format('d M Y') }}
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

                <a
                    href="{{ route('member.payments') }}"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Pay now
                </a>
            </div>
        @endif
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Membership Status --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 hover:shadow-md transition">
            <p class="text-sm font-semibold text-gray-500">Membership Status</p>

            <div class="mt-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold text-white {{ $statusUi['class'] }}">
                    {{ $statusUi['label'] }}
                </span>
            </div>

            <p class="text-sm text-gray-600 mt-3">
                {{ $statusUi['description'] }}
            </p>
        </div>

        {{-- Amount Due --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 hover:shadow-md transition">
            <p class="text-sm font-semibold text-gray-500">Amount Due</p>

            <div class="mt-3">
                @if($amountDue > 0)
                    <div class="text-2xl font-bold text-gray-900">
                        KES {{ number_format($amountDue) }}
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        Pay to keep your membership in good standing.
                    </p>
                @else
                    <div class="text-lg font-semibold text-emerald-700">
                        No dues
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        You are up to date.
                    </p>
                @endif
            </div>
        </div>

        {{-- Next Payment Deadline --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 hover:shadow-md transition">
            <p class="text-sm font-semibold text-gray-500">Next Payment Deadline</p>

            <div class="mt-3">
                @if($nextDeadline)
                    @php $deadline = \Carbon\Carbon::parse($nextDeadline); @endphp

                    <div class="text-lg font-semibold {{ now()->gt($deadline) ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $deadline->format('d M Y') }}
                    </div>

                    <p class="text-sm text-gray-600 mt-2">
                        {{ now()->gt($deadline) ? 'Deadline passed — please pay as soon as possible.' : 'Pay before the deadline to avoid penalties.' }}
                    </p>
                @else
                    <div class="text-lg font-semibold text-gray-700">
                        No upcoming payment
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        Nothing scheduled right now.
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Dependents & Beneficiaries --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Dependents --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 hover:shadow-md transition">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Dependents</h3>
                    <p class="text-sm text-gray-600 mt-1">Track your dependents and their status.</p>
                </div>

                <a href="{{ route('member.dependents') }}"
                   class="text-indigo-700 hover:text-indigo-900 text-sm font-semibold">
                    Manage →
                </a>
            </div>

            <div class="mt-5 space-y-3 text-sm">
                <div class="flex justify-between text-gray-700">
                    <span>Total</span><span class="font-semibold">{{ $dependentsCount }}</span>
                </div>
                <div class="flex justify-between text-gray-700">
                    <span>Active</span><span class="font-semibold text-emerald-700">{{ $activeDependents }}</span>
                </div>
                <div class="flex justify-between text-gray-700">
                    <span>Deceased</span><span class="font-semibold">{{ $deceasedDependents }}</span>
                </div>
            </div>
        </div>

        {{-- Beneficiaries --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 hover:shadow-md transition">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Beneficiaries</h3>
                    <p class="text-sm text-gray-600 mt-1">Review and update your allocation.</p>
                </div>

                <a href="{{ route('member.beneficiaries') }}"
                   class="text-indigo-700 hover:text-indigo-900 text-sm font-semibold">
                    Manage →
                </a>
            </div>

            <div class="mt-5 space-y-3 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span>Total</span>
                    <span class="font-semibold">{{ $beneficiariesCount }}</span>
                </div>

                @if($pendingBeneficiaryChanges > 0)
                    <div class="mt-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-900">
                        <p class="text-sm font-semibold">
                            {{ $pendingBeneficiaryChanges }} change(s) pending approval
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Upcoming Approved Events --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Upcoming Approved Events</h3>
                <p class="text-sm text-gray-600 mt-1">These events have been approved and are scheduled.</p>
            </div>

            {{-- Optional: link to all events --}}
            {{-- <a href="{{ route('member.events') }}" class="text-indigo-700 hover:text-indigo-900 text-sm font-semibold">View all →</a> --}}
        </div>

        <div class="p-6">
            @if($approvedEvents->isEmpty())
                <div class="text-center py-10 space-y-2">
                    <div class="text-4xl">📅</div>
                    <p class="text-gray-900 font-semibold">No upcoming approved events</p>
                    <p class="text-sm text-gray-600">When an event is approved, it will show up here.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($approvedEvents as $event)
                        <div class="rounded-2xl border border-gray-200 p-5 hover:bg-gray-50 transition">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                <div class="min-w-0">
                                    <h4 class="text-lg font-semibold text-gray-900">
                                        {{ $event->details->title }}
                                    </h4>

                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ \Carbon\Carbon::parse($event->details->event_date)->format('d M Y, h:i A') }}
                                        @if(!empty($event->details->location))
                                            — {{ $event->details->location }}
                                        @endif
                                    </p>

                                    @if(!empty($event->details->description))
                                        <p class="text-sm text-gray-700 mt-3">
                                            {{ $event->details->description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="shrink-0">
                                    <a
                                        href="#"
                                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-white hover:bg-gray-50 text-indigo-700 font-semibold border transition
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>

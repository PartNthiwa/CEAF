<div class="max-w-6xl mx-auto px-6 py-8 space-y-8">

    {{-- Header --}}
    @if(auth()->user()->last_login_at)
    <p class="text-sm text-gray-500">
        Last login: {{ auth()->user()->last_login_at->diffForHumans() }}
    </p>
    @endif
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">
                Welcome, <span class="font-semibold text-gray-800">{{ auth()->user()->name ?? 'Member' }}</span>
            </p>
            <h1 class="text-2xl font-semibold text-gray-800">
                Carolina East Africa Foundation — Bereavement Registry
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                View your membership status, manage dependents/beneficiaries, and see approved events.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('member.payments') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow-sm transition">
                Make Payment
            </a>
            {{-- Optional additional quick action --}}
            {{-- <a href="{{ route('member.profile') }}"
               class="bg-white hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg shadow-sm border transition">
                My Profile
            </a> --}}
        </div>
    </div>

    {{-- Next Step / Alerts (highly recommended) --}}
    @if($membershipStatus === 'suspended')
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4">
            <div class="font-semibold">Your membership is suspended.</div>
            <div class="text-sm mt-1">Please contact support or clear outstanding requirements to restore access.</div>
        </div>
    @elseif($amountDue > 0)
        <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <div class="font-semibold">Payment required</div>
                <div class="text-sm mt-1">
                    Amount due: <span class="font-bold">KES {{ number_format($amountDue) }}</span>
                    @if($nextDeadline)
                        — deadline: <span class="font-semibold">{{ \Carbon\Carbon::parse($nextDeadline)->format('d M Y') }}</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('member.payments') }}"
               class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition">
                Pay now
            </a>
        </div>
    @endif

    {{-- Top Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Membership Status --}}
       {{-- Membership Status --}}
        <div class="bg-white rounded-xl shadow p-6 hover:shadow-md transition">
            <h3 class="text-sm font-semibold text-gray-500 mb-2">Membership Status</h3>

            <span class="inline-block px-3 py-1 text-white font-semibold rounded-full {{ $statusUi['class'] }}">
                {{ $statusUi['label'] }}
            </span>

            <p class="text-sm text-gray-600 mt-2">
                {{ $statusUi['description'] }}
            </p>
        </div>


        {{-- Amount Due --}}
        <div class="bg-white rounded-xl shadow p-6 hover:shadow-md transition">
            <h3 class="text-sm font-semibold text-gray-500 mb-2">Amount Due</h3>
            @if($amountDue > 0)
                <div class="text-2xl font-bold text-gray-800">KES {{ number_format($amountDue) }}</div>
                <p class="text-sm text-gray-500 mt-1">Pay to keep your membership in good standing.</p>
            @else
                <div class="text-gray-600 font-semibold">No dues</div>
                <p class="text-sm text-gray-500 mt-1">You are up to date.</p>
            @endif
        </div>

        {{-- Next Payment Deadline --}}
        <div class="bg-white rounded-xl shadow p-6 hover:shadow-md transition">
            <h3 class="text-sm font-semibold text-gray-500 mb-2">Next Payment Deadline</h3>
            @if($nextDeadline)
                @php $deadline = \Carbon\Carbon::parse($nextDeadline); @endphp
                <div class="text-lg font-semibold {{ now()->gt($deadline) ? 'text-red-600' : 'text-gray-800' }}">
                    {{ $deadline->format('d M Y') }}
                </div>
                @if(now()->gt($deadline))
                    <p class="text-sm text-red-600 mt-1 font-medium">Deadline passed — please pay as soon as possible.</p>
                @endif
            @else
                <div class="text-gray-600 font-semibold">No upcoming payment</div>
                <p class="text-sm text-gray-500 mt-1">Nothing scheduled right now.</p>
            @endif
        </div>

    </div>

    {{-- Dependents & Beneficiaries --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Dependents --}}
        <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between gap-4">
                <h3 class="text-lg font-semibold text-gray-800">Dependents</h3>
                <a href="{{ route('member.dependents') }}"
                   class="text-blue-600 hover:underline text-sm font-semibold">
                    Manage →
                </a>
            </div>

            <div class="mt-4 space-y-2 text-sm text-gray-600">
                <div class="flex justify-between"><span>Total</span><span class="font-semibold">{{ $dependentsCount }}</span></div>
                <div class="flex justify-between"><span>Active</span><span class="text-green-600 font-semibold">{{ $activeDependents }}</span></div>
                <div class="flex justify-between"><span>Deceased</span><span class="text-gray-700 font-semibold">{{ $deceasedDependents }}</span></div>
            </div>
        </div>

        {{-- Beneficiaries --}}
        <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between gap-4">
                <h3 class="text-lg font-semibold text-gray-800">Beneficiaries</h3>
                <a href="{{ route('member.beneficiaries') }}"
                   class="text-blue-600 hover:underline text-sm font-semibold">
                    Manage →
                </a>
            </div>

            <div class="mt-4 space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Total</span>
                    <span class="font-semibold">{{ $beneficiariesCount }}</span>
                </div>

                @if($pendingBeneficiaryChanges > 0)
                    <div class="mt-3 text-yellow-800 bg-yellow-100 px-3 py-2 rounded text-sm font-medium">
                        {{ $pendingBeneficiaryChanges }} change(s) pending approval
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Upcoming Approved Events --}}
    <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
        <div class="flex items-start justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-800">Upcoming Approved Events</h3>
            {{-- Optional: link to all events --}}
            {{-- <a href="{{ route('member.events') }}" class="text-blue-600 hover:underline text-sm font-semibold">View all →</a> --}}
        </div>

        @if($approvedEvents->isEmpty())
            <p class="text-gray-600 mt-4">No upcoming approved events found.</p>
        @else
            <div class="mt-4 space-y-4">
                @foreach($approvedEvents as $event)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                            <div>
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
                                    <p class="text-sm text-gray-700 mt-2">
                                        {{ $event->details->description }}
                                    </p>
                                @endif
                            </div>

                            <div class="shrink-0">
                                <a href="#"
                                   class="inline-flex items-center justify-center bg-white hover:bg-gray-50 text-blue-700 font-semibold px-4 py-2 rounded-lg border transition">
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

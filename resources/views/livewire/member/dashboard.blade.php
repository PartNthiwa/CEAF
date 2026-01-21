<div class="max-w-6xl mx-auto px-6 py-8 space-y-8">

    {{-- Dashboard Header / Quick Actions --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('member.payments') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow-sm transition">
                Make Payment
            </a>
            <!-- <a href="{{ route('member.dependents') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium px-4 py-2 rounded-lg shadow-sm transition">
                Manage Dependents
            </a>
            <a href="{{ route('member.beneficiaries') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium px-4 py-2 rounded-lg shadow-sm transition">
                Manage Beneficiaries
            </a> -->
        </div>
    </div>

    {{-- Main Dashboard Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Membership Status --}}
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between hover:shadow-md transition">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">Membership Status</h3>
            <span class="px-3 py-1 text-white font-semibold rounded-full text-center
                @if($membershipStatus === 'active') bg-green-600
                @elseif($membershipStatus === 'late') bg-yellow-500
                @elseif($membershipStatus === 'suspended') bg-red-600
                @else bg-gray-400
                @endif
            ">
                {{ ucfirst($membershipStatus) }}
            </span>
        </div>

        {{-- Amount Due --}}
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between hover:shadow-md transition">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">Amount Due</h3>
            @if(!is_null($amountDue))
                <span class="text-blue-700 text-xl font-bold">
                    KES {{ number_format($amountDue) }}
                </span>
            @else
                <span class="text-gray-400">No dues</span>
            @endif
        </div>

        {{-- Next Payment Deadline --}}
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between hover:shadow-md transition">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">Next Payment Deadline</h3>
            @if(!is_null($nextDeadline))
                <span class="text-gray-800 font-medium
                    @if($nextDeadline && now()->gt(\Carbon\Carbon::parse($nextDeadline))) text-red-600 font-bold @endif
                ">
                    {{ \Carbon\Carbon::parse($nextDeadline)->format('d M Y') }}
                </span>
            @else
                <span class="text-gray-400">No upcoming payment</span>
            @endif
        </div>

    </div>

    {{-- Dependents Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dependents</h3>

            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Total</span>
                    <span class="font-semibold">{{ $dependentsCount }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Active</span>
                    <span class="text-green-600 font-semibold">{{ $activeDependents }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Deceased</span>
                    <span class="text-gray-600 font-semibold">{{ $deceasedDependents }}</span>
                </div>
            </div>

            <a href="{{ route('member.dependents') }}"
               class="inline-block mt-4 text-blue-600 hover:underline text-sm font-semibold">
                Manage Dependents →
            </a>
        </div>

        {{-- Beneficiaries Summary --}}
        <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Beneficiaries</h3>

            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Total Beneficiaries</span>
                    <span class="font-semibold">{{ $beneficiariesCount }}</span>
                </div>

                @if($pendingBeneficiaryChanges > 0)
                    <div class="text-yellow-700 bg-yellow-100 px-3 py-2 rounded text-sm font-medium">
                        {{ $pendingBeneficiaryChanges }} change(s) pending approval
                    </div>
                @endif
            </div>

            <a href="{{ route('member.beneficiaries') }}"
               class="inline-block mt-4 text-blue-600 hover:underline text-sm font-semibold">
                Manage Beneficiaries →
            </a>
        </div>

    </div>

</div>

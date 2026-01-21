<div class="p-6">

    {{-- Dashboard Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Membership Status Card --}}
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between">
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

        {{-- Amount Due Card --}}
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">Amount Due</h3>
            @if(!is_null($amountDue))
                <span class="text-blue-700 text-xl font-bold">
                    KES {{ number_format($amountDue) }}
                </span>
            @else
                <span class="text-gray-400">No dues</span>
            @endif
        </div>

        {{-- Next Payment Deadline Card --}}
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between">
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

    {{-- Optional: Quick Action Buttons --}}
    <div class="mt-6 flex flex-wrap gap-4">
        <a href="{{ route('member.payments') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg">
            Make Payment
        </a>
        <a href="{{ route('member.dependents') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold px-4 py-2 rounded-lg">
            Manage Dependents
        </a>
        <a href="{{ route('member.beneficiaries') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold px-4 py-2 rounded-lg">
            Manage Beneficiaries
        </a>
    </div>

</div>

<div class="p-6 bg-white rounded shadow">

    <!-- Current Page Title -->
    <div class="mb-6 border-b pb-4 text-sm text-gray-700">
        Home &gt;
        @if(request()->routeIs('admin.dashboard'))
            Member Overview
        @elseif(request()->routeIs('admin.configuration'))
            Configuration Settings
        @elseif(request()->routeIs('admin.seed-cycle'))
            Payment Cycle Management
        @elseif(request()->routeIs('admin.beneficiary.requests'))
            Beneficiary Requests
        @else
            Admin Panel
        @endif
    </div>

    <!-- Stats Cards -->
    @if(request()->routeIs('admin.dashboard'))
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-100 p-4 rounded text-center">
            <div class="text-gray-500 text-sm">Total Members</div>
            <div class="text-xl font-bold">{{ $totalMembers }}</div>
        </div>
        <div class="bg-green-100 p-4 rounded text-center">
            <div class="text-gray-500 text-sm">Active</div>
            <div class="text-xl font-bold">{{ $activeMembers }}</div>
        </div>
        <div class="bg-yellow-100 p-4 rounded text-center">
            <div class="text-gray-500 text-sm">Late</div>
            <div class="text-xl font-bold">{{ $lateMembers }}</div>
        </div>
        <div class="bg-red-100 p-4 rounded text-center">
            <div class="text-gray-500 text-sm">Suspended</div>
            <div class="text-xl font-bold">{{ $suspendedMembers }}</div>
        </div>
    </div>
    @endif

    <!-- Optional Button -->
    @if(request()->routeIs('admin.dashboard'))
    <div class="mb-4">
        <button wire:click="enforceLatePayments"
                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
            Enforce Late Payments
        </button>
    </div>
    @endif

    <!-- Members Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">#</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Email</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Joined</th>
                </tr>
            </thead>
           <tbody>
                @foreach($members as $index => $member)
                <tr class="{{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                    <td class="px-4 py-2 text-sm">{{ $members->firstItem() + $index }}</td>
                    <td class="px-4 py-2 text-sm">{{ $member->user->name }}</td>
                    <td class="px-4 py-2 text-sm">{{ $member->user->email }}</td>
                    <td class="px-4 py-2 text-sm">
                        @if($member->membership_status === 'active')
                            <span class="text-green-600 font-semibold">Active</span>
                        @elseif($member->membership_status === 'late')
                            <span class="text-yellow-600 font-semibold">Late</span>
                        @elseif($member->membership_status === 'suspended')
                            <span class="text-red-600 font-semibold">Suspended</span>
                        @elseif($member->membership_status === 'terminated')
                            <span class="text-gray-600 font-semibold">Terminated</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-sm">{{ $member->join_date->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $members->links() }}
    </div>
</div>

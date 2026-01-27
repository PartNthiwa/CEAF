<div class="p-6 bg-gray-50 min-h-screen space-y-8">

    {{-- Breadcrumb / Page Title --}}
    <div class="text-sm text-gray-600 border-b pb-3">
        Home &gt; <span class="font-semibold text-gray-800">Admin Dashboard</span>
    </div>

    {{-- MEMBER HEALTH --}}
    <section>
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Member Health</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <x-admin.stat title="Total Members" :value="$totalMembers" class="card" />
            <x-admin.stat title="Active" :value="$activeMembers" color="green" class="card" />
            <x-admin.stat title="Late" :value="$lateMembers" color="yellow" class="card" />
            <x-admin.stat title="Suspended" :value="$suspendedMembers" color="red" class="card" />
            <x-admin.stat title="Terminated" :value="$terminatedMembers" color="gray" class="card" />
        </div>
    </section>

    {{-- FINANCIAL HEALTH --}}
    <section>
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Financial Health ({{ now()->year }})
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <x-admin.stat
                title="Seed Balance"
                :value="'$' . number_format($seedBalance)"
                color="blue"
                class="card"
            />

            <x-admin.stat
                title="Seed Spent"
                :value="'$' . number_format($seedSpent)"
                class="card"
            />

            <x-admin.stat
                title="Open Replenishments"
                :value="$openReplenishments"
                color="yellow"
                class="card"
            />
        </div>
    </section>

    {{-- ACTION REQUIRED --}}
    @if($alerts->isNotEmpty())
    <section class="bg-yellow-50 border border-yellow-200 rounded-lg p-5">
        <h3 class="text-sm font-semibold text-yellow-800 mb-3">
            Action Required
        </h3>

        <ul class="space-y-2 text-sm text-yellow-900">
            @foreach($alerts as $alert)
                <li>• {{ $alert }}</li>
            @endforeach
        </ul>
    </section>
    @endif

    {{-- MEMBERS TABLE --}}
    <section class="bg-white border rounded-lg shadow-md">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Members</h2>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <!-- Desktop -->
            <table class="min-w-full text-sm table-auto">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">#</th>
                        <th class="px-6 py-3 text-left font-medium">Name</th>
                        <th class="px-6 py-3 text-left font-medium">Email</th>
                        <th class="px-6 py-3 text-left font-medium">Status</th>
                        <th class="px-6 py-3 text-left font-medium">Amount Due</th>
                        <th class="px-6 py-3 text-left font-medium">Next Deadline</th>
                        <th class="px-6 py-3 text-left font-medium">Action</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">
                    @foreach($members as $index => $member)
                        <tr class="border-t hover:bg-gray-100 transition-all duration-300">
                            <td class="px-6 py-2">
                                {{ $members->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-2 font-medium">
                                {{ $member->user->name }}
                            </td>
                            <td class="px-6 py-2">
                                {{ $member->user->email }}
                            </td>
                            <td class="px-6 py-2 capitalize">
                                {{ $member->membership_status }}
                            </td>
                            <td class="px-6 py-2">
                                {{ $member->amount_due > 0 ? '$'.number_format($member->amount_due) : '—' }}
                            </td>
                            <td class="px-6 py-2">
                                {{ $member->next_deadline?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-2">
                                <a href="{{ route('admin.show', $member) }}"
                                   class="text-blue-600 hover:underline font-semibold">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="md:hidden">
            <!-- Mobile Cards -->
            @foreach($members as $index => $member)
                <div class="p-4 border-b">
                    <div class="flex justify-between items-center">
                        <div class="font-medium text-gray-800">
                            {{ $members->firstItem() + $index }}. {{ $member->user->name }}
                        </div>
                        <a href="{{ route('admin.show', $member) }}" class="text-blue-600 hover:underline">
                            View
                        </a>
                    </div>
                    <div class="text-gray-600 text-sm mt-2">
                        <div>Email: {{ $member->user->email }}</div>
                        <div>Status: {{ $member->membership_status }}</div>
                        <div>Amount Due: {{ $member->amount_due > 0 ? '$'.number_format($member->amount_due) : '—' }}</div>
                        <div>Next Deadline: {{ $member->next_deadline?->format('d M Y') ?? '—' }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="p-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-3">
                <div>
                    <span class="text-sm text-gray-600">
                        Showing {{ $members->firstItem() }} to {{ $members->lastItem() }} of {{ $members->total() }} members
                    </span>
                </div>
             <div class="px-4 py-4 border-t">
                    <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</div>

                    <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full text-left text-red-500 hover:underline">
                            Logout
                        </button>
                    </form>
                </div>

                <div>
                    {{ $members->links() }}
                </div>
            </div>
        </div>
    </section>
</div>


a
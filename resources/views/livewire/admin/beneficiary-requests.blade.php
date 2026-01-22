<div class="p-6 min-h-screen bg-gray-100">
    <h2 class="text-2xl font-bold mb-6">Pending Beneficiary Change Requests</h2>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Member</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Submitted At</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($requests as $r)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $r->member->full_name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $r->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ ucfirst($r->status) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                            No pending requests.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 border-t">
            {{ $requests->links() }}
        </div>
    </div>
</div>

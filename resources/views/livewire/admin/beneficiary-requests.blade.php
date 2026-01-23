<div class="p-6 min-h-screen bg-gray-100" x-data="{ showPayloadModal: @entangle('modalOpen') }">

    <h2 class="text-2xl font-bold mb-6">Pending Beneficiary Change Requests</h2>

    @if ($errors->any())
        <div class="mb-4 rounded bg-red-50 border border-red-200 p-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Member</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Submitted At</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($requests as $r)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $r->member->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $r->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="{{ $r->status === 'pending' ? 'text-yellow-600' : ($r->status === 'approved' ? 'text-green-600' : 'text-red-600') }}">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm flex space-x-2">
                            @if($r->isPending())
                                <!-- View Payload -->
                                <button wire:click="viewPayload({{ $r->id }})" class="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300 text-sm">View</button>
                                <!-- Approve -->
                                <button wire:click="approve({{ $r->id }})" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">Approve</button>
                                <!-- Reject -->
                                <button wire:click="reject({{ $r->id }})" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm">Reject</button>
                            @else
                                <span class="text-gray-400 italic">No actions</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
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

    <!-- Payload Modal -->
    <div x-show="showPayloadModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow max-w-lg w-full">
            <h3 class="text-lg font-semibold mb-4">Requested Changes</h3>

            @if(!empty($currentPayload))
                <table class="min-w-full divide-y divide-gray-200 mb-4">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Beneficiary</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Contact</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currentPayload as $bId => $data)
                            <tr class="bg-white">
                                <td class="px-4 py-2 text-sm">{{ $data['name'] ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm">{{ $data['contact'] ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm">{{ $data['percentage'] ?? '-' }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500">No details available.</p>
            @endif

            <div class="flex justify-end space-x-2">
                <button @click="showPayloadModal = false" wire:click="closeModal" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Close</button>
            </div>
        </div>
    </div>
</div>

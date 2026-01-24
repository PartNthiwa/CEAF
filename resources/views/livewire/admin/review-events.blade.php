<div class="p-6 bg-white rounded shadow space-y-4">

    <h2 class="text-2xl font-bold">Event Requests</h2>

    @if (session()->has('success'))
        <div class="p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-3 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search --}}
    <input type="text" wire:model.debounce.300ms="search" placeholder="Search by person"
        class="border rounded px-3 py-2 w-full sm:w-64 mb-4">

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Member</th>
                    <th class="px-4 py-2 text-left">Person</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Submitted</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>

            <tbody>
               @forelse($events as $event)
                <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                    <td class="px-4 py-2">{{ $events->firstItem() + $loop->index }}</td>
                    <td class="px-4 py-2">{{ $event->member->user->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $event->person->full_name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 capitalize">{{ $event->status }}</td>
                    <td class="px-4 py-2">{{ $event->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-2 space-x-2 flex">
                        @if($event->status === 'submitted' || $event->status === 'under_review')
                            <button wire:click="approve({{ $event->id }})"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                                Approve
                            </button>
                            <button wire:click="reject({{ $event->id }})"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                Reject
                            </button>
                        @else
                            <span class="text-gray-500">No actions</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                        No events found.
                    </td>
                </tr>
@endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $events->links() }}
    </div>

</div>

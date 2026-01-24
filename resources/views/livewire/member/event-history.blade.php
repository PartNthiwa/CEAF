<div class="min-h-screen bg-gray-100">
    <div class="max-w-6xl mx-auto p-6 space-y-6">

        <h2 class="text-2xl font-bold text-gray-800">
            My Event Requests
        </h2>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">#</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Deceased</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Submitted</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Documents</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($events ?? [] as $event)
                        <tr>
                            <td class="px-6 py-4">
                                {{ isset($events) && method_exists($events, 'firstItem') ? $events->firstItem() + $loop->index : $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $event->person->full_name ?? $event->person->name ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $badge = match($event->status) {
                                        'submitted'    => 'bg-blue-100 text-blue-800',
                                        'under_review' => 'bg-yellow-100 text-yellow-800',
                                        'approved'     => 'bg-green-100 text-green-800',
                                        'rejected'     => 'bg-red-100 text-red-800',
                                        default        => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp

                                <span class="px-3 py-1 rounded-full text-sm {{ $badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $event->status)) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $event->created_at?->format('d M Y') ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 space-x-2">
                                @if(!empty($event->documents))
                                    @foreach($event->documents as $doc)
                                        <a href="{{ Storage::url($doc->path) }}"
                                           target="_blank"
                                           class="text-indigo-600 text-sm underline">
                                            {{ strtoupper($doc->type) }}
                                        </a>
                                    @endforeach
                                @else
                                    <span class="text-gray-400 text-sm">No documents</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                No event requests submitted yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if(isset($events) && method_exists($events, 'links'))
                <div class="p-4 border-t">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

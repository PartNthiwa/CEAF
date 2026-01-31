<div class="min-h-screen bg-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-6">

        {{-- Header --}}
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900">My Event Requests</h2>
            <p class="text-sm text-gray-600">
                Track submitted requests and view uploaded documents.
            </p>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Requests</h3>
                <p class="text-sm text-gray-500">
                    {{ isset($events) && method_exists($events, 'total') ? $events->total() : count($events ?? []) }} total
                </p>
            </div>

            {{-- Responsive wrapper --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Deceased</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Documents</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse(($events ?? []) as $event)
                            @php
                                $index = isset($events) && method_exists($events, 'firstItem')
                                    ? $events->firstItem() + $loop->index
                                    : $loop->iteration;

                                $statusLabel = ucfirst(str_replace('_', ' ', $event->status ?? 'unknown'));

                                $badge = match($event->status) {
                                    'submitted'    => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
                                    'under_review' => 'bg-amber-50 text-amber-800 ring-amber-200',
                                    'approved'     => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                    'rejected'     => 'bg-red-50 text-red-700 ring-red-200',
                                    default        => 'bg-gray-100 text-gray-700 ring-gray-200',
                                };

                                // Support either array, collection, or json-cast documents
                                $docs = $event->documents ?? [];
                                $docsCount = is_countable($docs) ? count($docs) : 0;

                                $personName =
                                    $event->person->full_name
                                    ?? $event->person->name
                                    ?? $event->person_name
                                    ?? 'N/A';
                            @endphp

                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $index }}
                                </td>

                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">
                                        {{ $personName }}
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold ring-1 {{ $badge }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $event->created_at?->format('d M Y') ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if($docsCount > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($docs as $doc)
                                                @php
                                                    $type = strtoupper($doc->type ?? 'DOC');
                                                    $path = $doc->path ?? $doc->file_path ?? null;
                                                @endphp

                                                @if($path)
                                                    <a
                                                        href="{{ Storage::url($path) }}"
                                                        target="_blank"
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                               bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200 hover:bg-indigo-100 transition"
                                                    >
                                                        {{ $type }}
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                                 bg-gray-100 text-gray-700 ring-1 ring-gray-200">
                                                        {{ $type }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">No documents</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <div class="space-y-2">
                                        <div class="text-4xl">🗂️</div>
                                        <p class="text-gray-800 font-semibold">No event requests yet</p>
                                        <p class="text-sm text-gray-500">
                                            When you submit an event request, it will appear here.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(isset($events) && method_exists($events, 'links'))
                <div class="px-6 py-4 border-t">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

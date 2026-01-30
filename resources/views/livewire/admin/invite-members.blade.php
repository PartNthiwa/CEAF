<div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6">

    {{-- Header + Navigation --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Invite Members</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Invite Members</h1>
            <p class="text-sm text-gray-600 mt-1">
                Send invitations to new members and track their status.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2">
            {{-- Back --}}
            <button
                type="button"
                onclick="window.history.back()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                       bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold
                       hover:bg-gray-200 transition"
            >
                ← Back
            </button>

            {{-- Navigation buttons (update routes if needed) --}}
            <a
                href="{{ route('admin.dashboard') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                       bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-lg font-semibold
                       hover:bg-gray-50 transition"
            >
                Dashboard
            </a>

            <a
                href="{{ route('admin.members-list') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                       bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-lg font-semibold
                       hover:bg-gray-50 transition"
            >
                Members
            </a>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Invite Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Send an Invitation</h2>
            <p class="text-sm text-gray-600 mt-1">
                Enter the member’s email address and send them an invite link.
            </p>
        </div>

        <div class="p-4 sm:p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    wire:model.defer="email"
                    placeholder="member@example.com"
                    class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                >
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between">
                <button
                    type="button"
                    wire:click="sendInvite"
                    wire:loading.attr="disabled"
                    wire:target="sendInvite"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                           bg-blue-600 hover:bg-blue-700 text-white font-semibold
                           px-5 py-2 rounded-lg transition disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="sendInvite">Send Invitation</span>
                    <span wire:loading wire:target="sendInvite">Sending...</span>
                </button>

                <p class="text-xs text-gray-500">
                    Tip: invitations can expire automatically if you set an expiry date.
                </p>
            </div>
        </div>
    </div>

    {{-- Recent Invitations --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Recent Invitations</h2>
                <p class="text-sm text-gray-600 mt-1">Latest invitation attempts and statuses.</p>
            </div>
            <div class="text-sm text-gray-500">
                {{ $invites->count() }} total
            </div>
        </div>

        <div class="divide-y">
            @forelse($invites as $inv)
                @php
                    $isUsed = (bool) $inv->used_at;
                    $isExpired = $inv->expires_at && now()->gt($inv->expires_at);

                    $badge = $isUsed
                        ? 'bg-green-50 text-green-700 ring-green-600/20'
                        : ($isExpired
                            ? 'bg-red-50 text-red-700 ring-red-600/20'
                            : 'bg-amber-50 text-amber-700 ring-amber-600/20');

                    $statusText = $isUsed ? 'Used' : ($isExpired ? 'Expired' : 'Active');
                @endphp

                <div class="p-4 sm:p-6 hover:bg-gray-50 transition">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div class="min-w-0">
                            <div class="font-semibold text-gray-900 break-all">
                                {{ $inv->email }}
                            </div>

                            <div class="text-sm text-gray-600 mt-1">
                                @if($inv->expires_at)
                                    Expires: <span class="font-medium text-gray-800">{{ $inv->expires_at->format('d M Y') }}</span>
                                @else
                                    <span class="text-gray-500">No expiry date set</span>
                                @endif
                            </div>
                        </div>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold ring-1 ring-inset {{ $badge }}">
                            {{ $statusText }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-gray-500">
                    <div class="text-4xl mb-2">✉️</div>
                    <p>No invitations yet.</p>
                    <p class="text-sm text-gray-400 mt-1">Send your first invite using the form above.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="text-xs text-gray-500 pt-2">
        &copy; {{ now()->year }} Carolina East Africa Foundation
    </div>
</div>

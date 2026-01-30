<div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6">

    {{-- Header + Navigation --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                <div>
                    <div class="text-sm text-gray-600">
                        Home &gt; <span class="font-semibold text-gray-900">Annual Payment Configuration</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">
                        Annual Payment Configuration – {{ $year }}
                    </h2>

                    <p class="text-sm text-gray-600 mt-1 max-w-2xl">
                        Configure seed parameters for the year. The system will use this to compute the total seed amount and member contributions later.
                    </p>
                </div>

                <div class="flex gap-2 w-full sm:w-auto">
                    <button
                        type="button"
                        onclick="window.history.back()"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                               bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold
                               hover:bg-gray-200 transition"
                    >
                        ← Back
                    </button>

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                               bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-lg font-semibold
                               hover:bg-gray-50 transition"
                    >
                        Dashboard
                    </a>
                </div>
            </div>

            {{-- Success Alert --}}
            <div class="mt-4 space-y-3">
                @if(session()->has('success'))
                    <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Grid: Form + Summary --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Form Card --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Configuration</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Enter values below. Total seed amount is calculated using your inputs.
                </p>
            </div>

            <div class="p-4 sm:p-6 space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    {{-- Year --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Year
                        </label>
                        <input
                            type="number"
                            wire:model="year"
                            readonly
                            class="mt-1 w-full rounded-lg border-gray-200 bg-gray-50 text-gray-700 cursor-not-allowed"
                        >
                        <p class="text-xs text-gray-500 mt-1">
                            Locked to the active configuration year.
                        </p>
                    </div>

                    {{-- Amount Per Event --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Amount Per Event
                        </label>
                        <input
                            type="number"
                            step="0.01"
                            wire:model.defer="amount_per_event"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="e.g. 5000"
                        >
                        @error('amount_per_event')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            How much each event should be funded.
                        </p>
                    </div>

                    {{-- Number of Events --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Number of Events Covered
                        </label>
                        <input
                            type="number"
                            wire:model.defer="number_of_events"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="e.g. 5"
                        >
                        @error('number_of_events')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            Number of funded events for the year.
                        </p>
                    </div>

                </div>

                {{-- Note --}}
                <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
                    <div class="font-semibold">How it works</div>
                    <div class="mt-2 text-blue-700 space-y-1">
                        <div>
                            Total Seed Amount = <span class="font-semibold">Amount Per Event × Number of Events</span>
                        </div>
                        <div>
                            Member contributions will be calculated automatically based on active members when the seed payment cycle is created.
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="pt-4 border-t flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <button
                        type="button"
                        onclick="window.history.back()"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        wire:click="save"
                        class="w-full sm:w-auto px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition"
                    >
                        Save Configuration
                    </button>
                </div>

                <div wire:loading wire:target="save" class="text-sm text-gray-500">
                    Saving configuration...
                </div>

            </div>
        </div>

        {{-- Summary / Preview --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Preview</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Quick summary of what these values mean.
                </p>
            </div>

            <div class="p-4 sm:p-6 space-y-4 text-sm">
                @php
                    $amount = (float) ($amount_per_event ?? 0);
                    $events = (int) ($number_of_events ?? 0);
                    $totalSeed = $amount * $events;
                @endphp

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <div class="text-xs text-gray-500">Amount Per Event</div>
                    <div class="text-lg font-bold text-gray-900">
                        {{ number_format($amount, 2) }}
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <div class="text-xs text-gray-500">Events Covered</div>
                    <div class="text-lg font-bold text-gray-900">
                        {{ $events }}
                    </div>
                </div>

                <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
                    <div class="text-xs text-blue-700">Total Seed Amount</div>
                    <div class="text-2xl font-extrabold text-blue-900">
                        {{ number_format($totalSeed, 2) }}
                    </div>
                    <p class="text-xs text-blue-700 mt-1">
                        Computed from your configuration.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

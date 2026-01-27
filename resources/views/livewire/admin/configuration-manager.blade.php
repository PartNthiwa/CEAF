<div class="p-6 bg-white rounded shadow space-y-6">

    <h2 class="text-2xl font-bold mb-4">
        Annual Payment Configuration – {{ $year }}
    </h2>

    @if(session()->has('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Year --}}
        <div class="space-y-1">
            <label class="block text-gray-700 font-medium">Year</label>
            <input type="number"
                   wire:model="year"
                   readonly
                   class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed">
        </div>

        {{-- Amount Per Event --}}
        <div class="space-y-1">
            <label class="block text-gray-700 font-medium">
                Amount Per Event
            </label>
            <input type="number"
                   step="0.01"
                   wire:model.defer="amount_per_event"
                   class="w-full border border-gray-300 rounded px-3 py-2"
                   placeholder="e.g. 5000">
        </div>

        {{-- Number of Events --}}
        <div class="space-y-1">
            <label class="block text-gray-700 font-medium">
                Number of Events Covered
            </label>
            <input type="number"
                   wire:model.defer="number_of_events"
                   class="w-full border border-gray-300 rounded px-3 py-2"
                   placeholder="e.g. 5">
        </div>

    </div>

    {{-- Derived explanation --}}
    <div class="bg-blue-50 text-blue-700 p-4 rounded text-sm">
        <strong>Note:</strong><br>
        Total Seed Amount = <em>Amount Per Event × Number of Events</em>.<br>
        Member contributions will be calculated automatically based on active members
        when the seed payment cycle is created.
    </div>

    <div class="flex justify-end">
        <button wire:click="save"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
            Save Configuration
        </button>
    </div>

</div>

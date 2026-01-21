<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Seed Payment Cycle Manager ({{ $year }})</h2>

    @if (session()->has('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 text-red-600">{{ session('error') }}</div>
    @endif

    <div class="mb-4 flex space-x-2">
        <button wire:click="createSeedCycle"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Create Seed Payment Cycle
        </button>

        <button wire:click="enforceLatePayments"
                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
            Enforce Late Payments
        </button>
    </div>

    <h3 class="font-semibold mb-2">Existing Seed Cycles:</h3>

    @if($cycles->isEmpty())
        <p>No seed payment cycles yet.</p>
    @else
        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-2 py-1">ID</th>
                    <th class="border px-2 py-1">Amount/Member</th>
                    <th class="border px-2 py-1">Status</th>
                    <th class="border px-2 py-1">Paid Members</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cycles as $cycle)
                    <tr>
                        <td class="border px-2 py-1">{{ $cycle->id }}</td>
                        <td class="border px-2 py-1">KES {{ number_format($cycle->amount_per_member) }}</td>
                        <td class="border px-2 py-1">{{ ucfirst($cycle->status) }}</td>
                        <td class="border px-2 py-1">{{ $cycle->paid_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

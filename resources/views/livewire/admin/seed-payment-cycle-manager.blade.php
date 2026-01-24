<div class="p-6 bg-white rounded shadow space-y-4">

    <h2 class="text-2xl font-bold mb-4">Create Seed Payment Cycle</h2>

    @if(session()->has('success'))
        <div class="p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="createSeedCycle" class="space-y-4">
        <div>
            <label class="block font-medium">Year</label>
            <input type="number" wire:model="year" class="border rounded px-3 py-2 w-full"/>
            @error('year') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium">Amount Per Member</label>
            <input type="number" wire:model="amount_per_member" class="border rounded px-3 py-2 w-full"/>
            @error('amount_per_member') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium">Start Date</label>
            <input type="date" wire:model="start_date" class="border rounded px-3 py-2 w-full"/>
            @error('start_date') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium">Due Date</label>
            <input type="date" wire:model="due_date" class="border rounded px-3 py-2 w-full"/>
            @error('due_date') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium">Late Deadline</label>
            <input type="date" wire:model="late_deadline" class="border rounded px-3 py-2 w-full"/>
            @error('late_deadline') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Create Cycle
        </button>
    </form>

</div>
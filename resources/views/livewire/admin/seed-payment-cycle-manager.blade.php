<div class="p-6 bg-white rounded shadow space-y-4">
    <h2 class="text-2xl font-bold mb-4">Create Seed Payment Cycle</h2>

    @if(session()->has('success'))
        <div class="p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="createSeedCycle" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Year Field -->
            <div>
                <label class="block font-medium">Year</label>
                <input type="number" wire:model="year" class="border rounded px-3 py-2 w-full"/>
                @error('year') <span class="text-red-600">{{ $message }}</span> @enderror
                <p class="text-sm text-gray-500 mt-1">Enter the year for this payment cycle.</p> 
            </div>

            <!-- Amount Per Member Field -->
            <div>
                <label class="block font-medium">Amount Per Member</label>
                <input type="number" wire:model="amount_per_member" class="border rounded px-3 py-2 w-full"/>
                @error('amount_per_member') <span class="text-red-600">{{ $message }}</span> @enderror
                <p class="text-sm text-gray-500 mt-1 ">This amount each member will pay for this cycle is <span class="text-red-600">auto-calculated</span></p> 
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Start Date Field -->
            <div>
                <label class="block font-medium">Start Date</label>
                <input type="date" wire:model="start_date" class="border rounded px-3 py-2 w-full"/>
                @error('start_date') <span class="text-red-600">{{ $message }}</span> @enderror
                <p class="text-sm text-gray-500 mt-1">Start date for the payment cycle.</p> 
            </div>

            <!-- Due Date Field -->
            <div>
                <label class="block font-medium">Due Date</label>
                <input type="date" wire:model="due_date" class="border rounded px-3 py-2 w-full"/>
                @error('due_date') <span class="text-red-600">{{ $message }}</span> @enderror
                <p class="text-sm text-gray-500 mt-1">Due date for the payment - 14 days.</p> 
            </div>
        </div>

        <div class="w-full">
            <!-- Late Deadline Field -->
            <div>
                <label class="block font-medium">Late Deadline</label>
                <input type="date" wire:model="late_deadline" class="border rounded px-3 py-2 w-full"/>
                @error('late_deadline') <span class="text-red-600">{{ $message }}</span> @enderror
                <p class="text-sm text-gray-500 mt-1">Late deadline with a late fee (30 days after due date).</p> 
            </div>
        </div>

       <div class="flex justify-end mt-4">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Create Seed Cycle
            </button>
        </div>
    </form>
</div>

<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">My Beneficiaries</h2>

    <div class="bg-white rounded shadow p-4 mb-6">
        <h3 class="font-semibold mb-2">Current Beneficiaries</h3>

        @foreach($beneficiaries as $b)
            <div class="flex justify-between border-b py-2">
                <span>{{ $b->full_name }} ({{ $b->relationship }})</span>
                <span>{{ $b->percentage }}%</span>
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="submitChangeRequest" class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-4">Request Beneficiary Change</h3>

        <input wire:model="full_name" class="border p-2 w-full mb-2" placeholder="Full Name">
        <input wire:model="relationship" class="border p-2 w-full mb-2" placeholder="Relationship">
        <input wire:model="percentage" type="number" class="border p-2 w-full mb-2" placeholder="Percentage">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Submit Request
        </button>
    </form>
</div>

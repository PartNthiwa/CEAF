<div class="p-6 bg-white rounded shadow">

    <!-- Navigation / Quick Links -->
    <div class="mb-6 border-b pb-4 flex space-x-4">
        <a href="{{ route('admin.dashboard') }}"
           class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">Dashboard</a>

        <a href="{{ route('admin.configuration') }}"
           class="px-3 py-1 rounded bg-blue-200  hover:bg-blue-300">Configuration</a>

        <a href="{{ route('admin.seed-cycle') }}"
           class="px-3 py-1 rounded bg-blue-200  hover:bg-blue-300">Payment Cycles</a>
    </div>

    <!-- Page Title -->
    <h2 class="text-xl font-bold mb-4">Admin Dashboard</h2>

    <!-- Enforce Late Payments Button -->
    <div class="mb-4">
        <button wire:click="enforceLatePayments"
                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
            Enforce Late Payments
        </button>
    </div>

    <!-- Stats -->
    <ul class="space-y-2">
        <li>Total Members: {{ $totalMembers }}</li>
        <li>Active: {{ $activeMembers }}</li>
        <li>Late: {{ $lateMembers }}</li>
        <li>Suspended: {{ $suspendedMembers }}</li>
    </ul>

</div>

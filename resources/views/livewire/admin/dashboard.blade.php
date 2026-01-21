<div class="p-6 bg-white rounded shadow">

    <!-- Navigation / Quick Links -->
    <div class="mb-6 border-b pb-4 flex space-x-4">
        <a href="{{ route('admin.dashboard') }}"
           class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">Dashboard</a>

        <a href="{{ route('admin.configuration') }}"
           class="px-3 py-1 rounded bg-blue-200  hover:bg-blue-300">Configuration</a>

        <!-- Add more admin links here later if needed -->
    </div>

    <!-- Page Title -->
    <h2 class="text-xl font-bold mb-4">Admin Dashboard</h2>

    <!-- Stats -->
    <ul class="space-y-2">
        <li>Total Members: {{ $totalMembers }}</li>
        <li>Active: {{ $activeMembers }}</li>
        <li>Late: {{ $lateMembers }}</li>
        <li>Suspended: {{ $suspendedMembers }}</li>
    </ul>

</div>

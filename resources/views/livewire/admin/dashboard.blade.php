<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Admin Dashboard</h2>

    <ul class="space-y-2">
        <li>Total Members: {{ $totalMembers }}</li>
        <li>Active: {{ $activeMembers }}</li>
        <li>Late: {{ $lateMembers }}</li>
        <li>Suspended: {{ $suspendedMembers }}</li>
    </ul>
</div>

<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Member Dashboard</h2>

    <div>
        <strong>Membership Status:</strong>
        <span class="px-2 py-1 rounded text-white
            @if($membershipStatus === 'active') bg-green-900
            @elseif($membershipStatus === 'late') bg-yellow-500
            @elseif($membershipStatus === 'suspended') bg-red-600
            @else bg-gray-400
            @endif
        ">
            {{ ucfirst($membershipStatus) }}
        </span>

    </div>
</div>

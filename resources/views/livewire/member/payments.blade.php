<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">My Payments</h2>
    <p>Here you will see your payment cycles and amounts due.</p>

    {{-- Placeholder table --}}
    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-1">Cycle</th>
                <th class="border px-2 py-1">Amount Due</th>
                <th class="border px-2 py-1">Status</th>
                <th class="border px-2 py-1">Deadline</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-2 py-1" colspan="4" class="text-center text-gray-400">No payments yet</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">System Configuration ({{ $year }})</h2>

    @foreach($configs as $key => $value)
        <div class="mb-3">
            <label class="block text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
            <input type="text" wire:model.defer="configs.{{ $key }}"
                   class="border rounded w-full p-2">
        </div>
    @endforeach

    <button wire:click="save"
            class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
        Save
    </button>

    @if (session()->has('success'))
        <div class="mt-2 text-green-600">
            {{ session('success') }}
        </div>
    @endif
</div>

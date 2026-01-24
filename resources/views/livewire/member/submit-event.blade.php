<div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow space-y-6">

    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Submit Death / Event Request</h2>

    <form wire:submit.prevent="submit" class="space-y-6">

        {{-- Person Dropdown --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Select Deceased Person
            </label>
            <select wire:model="person_id" class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Select Deceased Person --</option>
                @foreach ($persons as $person)
                    <option value="{{ $person->id }}">
                        {{ $person->first_name }} {{ $person->last_name }} ({{ $person->roles_label }})
                    </option>
                @endforeach
            </select>
            @error('person_id') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
            @enderror
        </div>

        {{-- File Uploads --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Death Certificate</label>
                <input type="file" wire:model="death_cert" class="w-full border p-2 rounded-lg">
                @error('death_cert') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
                @if ($death_cert)
                    <p class="text-sm text-gray-600 mt-1">Selected file: {{ $death_cert->getClientOriginalName() }}</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Burial Permit</label>
                <input type="file" wire:model="burial_permit" class="w-full border p-2 rounded-lg">
                @error('burial_permit') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
                @if ($burial_permit)
                    <p class="text-sm text-gray-600 mt-1">Selected file: {{ $burial_permit->getClientOriginalName() }}</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Newspaper Announcement</label>
                <input type="file" wire:model="newspaper" class="w-full border p-2 rounded-lg">
                @error('newspaper') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
                @if ($newspaper)
                    <p class="text-sm text-gray-600 mt-1">Selected file: {{ $newspaper->getClientOriginalName() }}</p>
                @endif
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold">
                Submit Event
            </button>
        </div>

    </form>

    {{-- Success / Error Flash Messages --}}
    @if (session()->has('success'))
        <p class="text-green-600 text-sm mt-2">{{ session('success') }}</p>
    @endif
    @if (session()->has('error'))
        <p class="text-red-600 text-sm mt-2">{{ session('error') }}</p>
    @endif

</div>

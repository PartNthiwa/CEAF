<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border overflow-hidden">

    {{-- Header --}}
    <div class="px-6 py-5 border-b">
        <h2 class="text-2xl font-bold text-gray-900">Submit Death / Event Request</h2>
        <p class="text-sm text-gray-600 mt-1">
            Select the deceased person and upload required documents to submit your request.
        </p>
    </div>

    {{-- Body --}}
    <div class="p-6 space-y-6">

        {{-- Flash Messages --}}
        <div class="space-y-3">
            @if (session()->has('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                    <p class="font-semibold">Success</p>
                    <p class="text-sm mt-1">{{ session('success') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <p class="font-semibold">Error</p>
                    <p class="text-sm mt-1">{{ session('error') }}</p>
                </div>
            @endif
        </div>

        <form wire:submit.prevent="submit" class="space-y-6">

            {{-- Person Dropdown --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Select Deceased Person
                </label>

                <select
                    wire:model="person_id"
                    class="w-full rounded-xl border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">— Select Deceased Person —</option>
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

            {{-- Documents --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Documents</h3>
                    <p class="text-xs text-gray-500">Upload clear photos/PDFs</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- Death Certificate --}}
                    <div class="rounded-xl border border-gray-200 p-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Death Certificate
                        </label>

                        <input type="file" wire:model="death_cert" class="w-full text-sm" />

                        @error('death_cert')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror

                        @if ($death_cert)
                            <p class="text-xs text-gray-600">
                                Selected: <span class="font-semibold">{{ $death_cert->getClientOriginalName() }}</span>
                            </p>
                        @endif

                        <div wire:loading wire:target="death_cert" class="text-xs text-gray-500">
                            Preparing file…
                        </div>
                    </div>

                    {{-- Burial Permit --}}
                    <div class="rounded-xl border border-gray-200 p-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Burial Permit
                        </label>

                        <input type="file" wire:model="burial_permit" class="w-full text-sm" />

                        @error('burial_permit')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror

                        @if ($burial_permit)
                            <p class="text-xs text-gray-600">
                                Selected: <span class="font-semibold">{{ $burial_permit->getClientOriginalName() }}</span>
                            </p>
                        @endif

                        <div wire:loading wire:target="burial_permit" class="text-xs text-gray-500">
                            Preparing file…
                        </div>
                    </div>

                    {{-- Newspaper Announcement --}}
                    <div class="rounded-xl border border-gray-200 p-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Newspaper Announcement
                        </label>

                        <input type="file" wire:model="newspaper" class="w-full text-sm" />

                        @error('newspaper')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror

                        @if ($newspaper)
                            <p class="text-xs text-gray-600">
                                Selected: <span class="font-semibold">{{ $newspaper->getClientOriginalName() }}</span>
                            </p>
                        @endif

                        <div wire:loading wire:target="newspaper" class="text-xs text-gray-500">
                            Preparing file…
                        </div>
                    </div>

                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center px-6 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold
                           transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    wire:loading.attr="disabled"
                    wire:target="submit,death_cert,burial_permit,newspaper"
                >
                    <span wire:loading.remove wire:target="submit,death_cert,burial_permit,newspaper">Submit Event</span>
                    <span wire:loading wire:target="submit,death_cert,burial_permit,newspaper">Submitting…</span>
                </button>
            </div>

        </form>

    </div>
</div>

<div
    class="min-h-screen bg-gray-100 bg-center bg-no-repeat bg-cover"
    style="background-image: url('{{ asset('images/beneficiary.png') }}');"
>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        {{-- Page Card --}}
        <div class="bg-white/80 backdrop-blur rounded-2xl shadow-lg ring-1 ring-black/5 p-6 sm:p-8 space-y-6">

            {{-- Header / Actions --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">My Beneficiaries</h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Add and manage how your benefit is allocated.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button
                        type="button"
                        wire:click="openModal"
                        class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <span>+</span>
                        <span>Add Beneficiary</span>
                    </button>

                    <button
                        type="button"
                        wire:click="enableChangeRequest"
                        class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
                    >
                        Request Change
                    </button>
                </div>
            </div>

            @php
                $total = (int) $beneficiaries->sum('percentage');
                $remaining = max(0, 100 - $total);
                $over = max(0, $total - 100);
                $barWidth = min(100, $total);
            @endphp

            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

                @if($pendingChangeRequest)
                    <div class="m-4 rounded-xl bg-amber-50 border border-amber-200 p-4">
                        <div class="flex items-start gap-3">
                            <div class="text-amber-600 text-lg leading-none">⏳</div>
                            <div>
                                <p class="text-amber-900 font-semibold">
                                    Beneficiary change request under review
                                </p>
                                <p class="text-sm text-amber-800 mt-1">
                                    Your requested changes will reflect once approved.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Header --}}
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Current Beneficiaries</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Keep your allocations at 100%.
                        </p>
                    </div>
                    <p class="text-sm text-gray-500">
                        {{ $beneficiaries->total() }} total
                    </p>
                </div>

                {{-- Allocation --}}
                <div class="px-6 py-4 border-b space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Total Allocation</span>
                        <span class="font-semibold {{ $over > 0 ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $total }}%
                        </span>
                    </div>

                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div
                            class="h-full transition-all {{ $over > 0 ? 'bg-red-500' : 'bg-indigo-600' }}"
                            style="width: {{ $barWidth }}%"
                        ></div>
                    </div>

                    @if($over > 0)
                        <p class="text-xs text-red-600">
                            Allocation exceeds 100% by {{ $over }}%. Please reduce some percentages.
                        </p>
                    @elseif($remaining > 0)
                        <p class="text-xs text-amber-700">
                            {{ $remaining }}% remaining to allocate.
                        </p>
                    @else
                        <p class="text-xs text-emerald-700">
                            Allocation complete (100%).
                        </p>
                    @endif
                </div>

                {{-- List --}}
                <div class="divide-y">
                    @forelse($beneficiaries as $b)
                        @php
                            $badge =
                                $b->percentage >= 50 ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' :
                                ($b->percentage >= 25 ? 'bg-indigo-50 text-indigo-700 ring-indigo-200' :
                                'bg-gray-100 text-gray-700 ring-gray-200');
                        @endphp

                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-4 min-w-0">
                                {{-- Avatar --}}
                                <div class="w-10 h-10 shrink-0 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold">
                                    {{ strtoupper(substr($b->name, 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 truncate">{{ $b->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ $b->contact }}</p>
                                </div>
                            </div>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold ring-1 {{ $badge }}">
                                {{ $b->percentage }}%
                            </span>
                        </div>
                    @empty
                        {{-- Empty --}}
                        <div class="px-6 py-16 text-center space-y-4">
                            <div class="text-4xl">🧾</div>
                            <div>
                                <p class="text-gray-700 font-semibold">No beneficiaries yet</p>
                                <p class="text-gray-500 text-sm mt-1">
                                    Add your first beneficiary to start allocating.
                                </p>
                            </div>

                            <button
                                type="button"
                                wire:click="openModal"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                + Add First Beneficiary
                            </button>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t">
                    {{ $beneficiaries->links() }}
                </div>
            </div>

            {{-- Modal --}}
            @if($showModal)
                <div
                    x-data
                    x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                >
                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl overflow-hidden">
                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between px-6 py-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $changeMode ? 'Request Beneficiary Change' : 'Add Beneficiary' }}
                            </h3>

                            <button
                                type="button"
                                wire:click="closeModal"
                                class="text-gray-400 hover:text-gray-600 text-2xl leading-none focus:outline-none"
                                aria-label="Close"
                            >
                                &times;
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <form
                            wire:submit.prevent="{{ $changeMode ? 'submitChangeRequest' : 'submit' }}"
                            class="p-6 space-y-5 max-h-[75vh] overflow-auto"
                        >
                            {{-- Select Person --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Select Dependent (Optional)
                                </label>

                                <select
                                    wire:model="selectedPerson"
                                    class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">— Choose beneficiary or dependent —</option>

                                    @foreach($beneficiaries as $b)
                                        <option
                                            value="beneficiary:{{ $b->id }}"
                                            @disabled($b->status === 'deceased' || $b->person?->deceased_at)
                                        >
                                            {{ $b->name }} (Existing Beneficiary)
                                            @if($b->status === 'deceased' || $b->person?->deceased_at)
                                                — Deceased
                                            @endif
                                        </option>
                                    @endforeach

                                    @foreach($dependents->where('status', 'active') as $d)
                                        <option value="dependent:{{ $d->id }}">
                                            {{ $d->name }} (Dependent)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Manual Fields --}}
                            {{-- IMPORTANT: this condition should match your Livewire property.
                               If you actually use $selectedPerson, replace $selectedDependent with the correct boolean. --}}
                            @if(!$selectedDependent)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Full Name
                                        </label>
                                        <input
                                            wire:model.defer="name"
                                            type="text"
                                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Contact
                                        </label>
                                        <input
                                            wire:model.defer="contact"
                                            type="text"
                                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>
                                </div>
                            @endif

                            {{-- Percentage --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Percentage Allocation
                                </label>

                                <input
                                    wire:model.defer="percentage"
                                    type="number"
                                    min="1"
                                    max="{{ $this->remainingAllocation }}"
                                    placeholder="1–{{ $this->remainingAllocation }}%"
                                    class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    @disabled($this->remainingAllocation === 0)
                                >

                                @if($this->remainingAllocation === 0)
                                    <p class="text-xs text-red-600 mt-1">
                                        Allocation complete — you cannot add more beneficiaries.
                                    </p>
                                @endif
                            </div>

                            @if (session()->has('error'))
                                <p class="text-red-600 text-sm">{{ session('error') }}</p>
                            @endif

                            {{-- Actions --}}
                            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
                                <button
                                    type="button"
                                    wire:click="closeModal"
                                    class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-100"
                                >
                                    Cancel
                                </button>

                                <button
                                    type="submit"
                                    class="px-5 py-2 rounded-lg text-white font-semibold
                                        {{ $changeMode ? 'bg-amber-500 hover:bg-amber-600' : 'bg-indigo-600 hover:bg-indigo-700' }}
                                        focus:outline-none focus:ring-2 focus:ring-offset-2
                                        {{ $changeMode ? 'focus:ring-amber-500' : 'focus:ring-indigo-500' }}"
                                >
                                    {{ $changeMode ? 'Submit Change Request' : 'Save Beneficiary' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

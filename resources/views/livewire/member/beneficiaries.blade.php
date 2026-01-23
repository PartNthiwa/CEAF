<div class="min-h-screen bg-gray-100 bg-contain bg-center" 
     style="background-image: url('/images/beneficiary.png');">
 
    <div class="max-w-5xl mx-auto p-6 space-y-8 bg-white/80 rounded-2xl shadow-lg">
   <div class="flex items-center justify-between">
    <h2 class="text-2xl font-bold text-gray-800">My Beneficiaries</h2>

<button wire:click="openModal"
    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition">
    + Add Beneficiary
</button>

<button wire:click="enableChangeRequest"
    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
    Request Beneficiary Change
</button>

</div>
@php
    $total = $beneficiaries->sum('percentage');
@endphp

<div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

    @if($pendingChangeRequest)
        <div class="mb-4 rounded-xl bg-yellow-50 border border-yellow-200 p-4">
            <div class="flex items-center gap-2 text-yellow-800 font-medium">
                ⏳ Beneficiary change request under review
            </div>
            <p class="text-sm text-yellow-700 mt-1">
                Your requested changes will reflect once approved.
            </p>
        </div>
    @endif

    {{-- Header --}}
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Current Beneficiaries</h3>
            
        </div>
        <p class="text-sm text-gray-400">{{ $beneficiaries->total() }} total</p>
    </div>

    {{-- Allocation Progress --}}
    <div class="px-6 py-4 border-b space-y-2">
        <div class="flex justify-between text-sm text-gray-600">
            <span>Total Allocation</span>
            <span class="font-semibold">{{ $total }}%</span>
        </div>

        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
            <div
                class="h-full transition-all
                    {{ $total > 100 ? 'bg-red-500' : 'bg-indigo-600' }}"
                style="width: {{ min($total, 100) }}%">
            </div>
        </div>

        @if($total < 100)
            <p class="text-xs text-amber-600">
                {{ 100 - $total }}% remaining to allocate
            </p>
        @elseif($total > 100)
            <p class="text-xs text-red-600">
                Allocation exceeds 100%
            </p>
        @endif
    </div>

    {{-- List --}}
    <div class="divide-y">
        @forelse($beneficiaries as $b)
            @php
                $badge =
                    $b->percentage >= 50 ? 'bg-green-50 text-green-700' :
                    ($b->percentage >= 25 ? 'bg-blue-50 text-blue-700' :
                    'bg-gray-100 text-gray-700');
            @endphp

            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">

                <div class="flex items-center gap-4">
                    {{-- Avatar --}}
                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700
                                flex items-center justify-center font-semibold">
                        {{ strtoupper(substr($b->name, 0, 1)) }}
                    </div>

                    <div>
                        <p class="font-medium text-gray-900">{{ $b->name }}</p>
                        <p class="text-sm text-gray-500">{{ $b->contact }}</p>
                    </div>
                </div>

                {{-- Percentage --}}
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $badge }}">
                    {{ $b->percentage }}%
                </span>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="px-6 py-16 text-center space-y-4">
                <div class="text-4xl">🧾</div>
                <p class="text-gray-500">
                    You haven’t added any beneficiaries yet.
                </p>
                <button
                    wire:click="openModal"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                           bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold">
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


@if($showModal)
<div
    x-data
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl mx-4">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b">
           <h3 class="text-lg font-semibold text-gray-800">
                {{ $changeMode ? 'Request Beneficiary Change' : 'Add Beneficiary' }}
            </h3>

            <button wire:click="closeModal"
                    class="text-gray-400 hover:text-gray-600 text-xl">
                &times;
            </button>
        </div>

        {{-- Modal Body --}}
      <form 
        wire:submit.prevent="{{ $changeMode ? 'submitChangeRequest' : 'submit' }}" 
        class="p-6 space-y-5"
    >

            {{-- Dependent --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Select Dependent (Optional)
                </label>
          <select wire:model="selectedPerson" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
    <option value="">— Choose beneficiary or dependent —</option>

    {{-- Existing beneficiaries --}}
    @foreach($beneficiaries as $b)
        <option value="beneficiary:{{ $b->id }}">
            {{ $b->name }} (Existing Beneficiary)
        </option>
    @endforeach

    {{-- Dependents --}}
    @foreach($dependents as $d)
        <option value="dependent:{{ $d->id }}" @disabled($d->status === 'deceased')>
            {{ $d->name }} (Dependent)
        </option>
    @endforeach
</select>

            </div>

            {{-- Manual Fields --}}
            @if(!$selectedDependent)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name
                        </label>
                        <input wire:model="name" type="text"
                               class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Contact
                        </label>
                        <input wire:model="contact" type="text"
                               class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            @endif

            {{-- Percentage --}}
         <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Percentage Allocation
                </label>
                <input wire:model="percentage"
                    type="number"
                    min="1"
                    max="{{ $this->remainingAllocation }}"
                    placeholder="1–{{ $this->remainingAllocation }}%"
                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">

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
            <div class="flex justify-end gap-3 pt-4">
                <button type="button"
                        wire:click="closeModal"
                        class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-100">
                    Cancel
                </button>

            <button type="submit"
                class="px-5 py-2 rounded-lg {{ $changeMode ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-semibold w-full">
                {{ $changeMode ? 'Submit Change Request' : 'Save Beneficiary' }}
            </button>


            </div>

        </form>
    </div>
</div>
@endif

    </div>
</div
   
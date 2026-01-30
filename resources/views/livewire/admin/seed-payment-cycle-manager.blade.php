<div class="w-full mx-auto px-3 sm:px-6 lg:px-10 2xl:px-16 py-6 space-y-6">




    {{-- Header + Navigation --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div>
            <div class="text-sm text-gray-600">
                Home &gt; <span class="font-semibold text-gray-900">Seed Payment Cycle</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">
                Create Seed Payment Cycle
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Configure the yearly seed contribution cycle for all members.
            </p>
        </div>

        <div class="flex gap-2 w-full sm:w-auto">
            <button
                type="button"
                onclick="window.history.back()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                       bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold
                       hover:bg-gray-200 transition"
            >
                ← Back
            </button>

            <a
                href="{{ route('admin.dashboard') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                       bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-lg font-semibold
                       hover:bg-gray-50 transition"
            >
                Dashboard
            </a>
        </div>
    </div>


    {{-- Important Warning --}}
<div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 sm:p-5">
    <div class="flex gap-3">
        <div class="shrink-0">
            <div class="h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-700">
                ⚠️
            </div>
        </div>

        <div class="min-w-0">
            <h3 class="text-sm sm:text-base font-semibold text-amber-900">
                Important: This action will populate member payment accounts
            </h3>

            <p class="mt-1 text-sm text-amber-900/90">
                Creating a Seed Payment Cycle will automatically generate (or update) each member’s record with
                the <span class="font-semibold">amount they are required to contribute</span> for the selected year.
                Please confirm the year, dates, and amount before submitting.
            </p>

            <div class="mt-3 flex flex-col sm:flex-row gap-2">
                <span class="inline-flex items-center justify-center rounded-lg bg-white px-3 py-1 text-xs font-semibold text-amber-900 border border-amber-200">
                    Affects all members
                </span>
                <span class="inline-flex items-center justify-center rounded-lg bg-white px-3 py-1 text-xs font-semibold text-amber-900 border border-amber-200">
                    Creates member dues for the year
                </span>
            </div>
        </div>
    </div>
</div>

    {{-- Success Alert --}}
    @if(session()->has('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Main Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <form wire:submit.prevent="createSeedCycle" class="p-6 sm:p-8 space-y-6">

            {{-- Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Year --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Year
                    </label>
                    <input
                        type="number"
                        wire:model="year"
                        class="mt-1 w-full rounded-lg border-gray-300
                               focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="e.g. 2026"
                    >
                    @error('year')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        The financial year for this cycle.
                    </p>
                </div>

                {{-- Amount --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Amount Per Member
                    </label>
                    <input
                        type="number"
                        wire:model="amount_per_member"
                        class="mt-1 w-full rounded-lg border-gray-300
                               focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Auto-calculated"
                    >
                    @error('amount_per_member')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        This is usually auto-calculated from total seed fund.
                    </p>
                </div>
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Start --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Start Date
                    </label>
                    <input
                        type="date"
                        wire:model="start_date"
                        class="mt-1 w-full rounded-lg border-gray-300
                               focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('start_date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        When members can start paying.
                    </p>
                </div>

                {{-- Due --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Due Date
                    </label>
                    <input
                        type="date"
                        wire:model="due_date"
                        class="mt-1 w-full rounded-lg border-gray-300
                               focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('due_date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        Normal payment deadline (14 days).
                    </p>
                </div>

                {{-- Late --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Late Deadline
                    </label>
                    <input
                        type="date"
                        wire:model="late_deadline"
                        class="mt-1 w-full rounded-lg border-gray-300
                               focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('late_deadline')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        After this, late fees apply (30 days).
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="pt-4 border-t flex flex-col sm:flex-row gap-3 sm:justify-end">
                <button
                    type="button"
                    onclick="window.history.back()"
                    class="w-full sm:w-auto px-4 py-2 rounded-lg
                           bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="w-full sm:w-auto px-4 py-2 rounded-lg
                           bg-indigo-600 text-white font-semibold
                           hover:bg-indigo-700 transition"
                >
                    Create Seed Cycle
                </button>
            </div>

            <div wire:loading class="text-sm text-gray-500">
                Creating seed cycle...
            </div>
        </form>
    </div>
</div>

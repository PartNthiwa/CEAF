<div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-6">


<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <nav class="text-sm text-gray-600">
        <a href="{{ route('member.dashboard') }}" class="font-semibold hover:text-gray-900">Dashboard</a>
        <span class="text-gray-400 mx-2">/</span>
        <span class="text-gray-900 font-semibold">Dependents</span>
    </nav>

    
</div>

    {{-- Page Header --}}
    <div class="flex flex-col gap-1">
        <h2 class="text-2xl font-bold text-gray-900">My Dependents</h2>
        <p class="text-sm text-gray-600">
            Manage your registered dependents. Deceased dependents are locked permanently.
        </p>
    </div>

    {{-- Add Dependent Card --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Add Dependent</h3>
            <p class="text-sm text-gray-500 mt-1">
                Fill in the details below to register a dependent.
            </p>
        </div>

        <form wire:submit.prevent="addDependent" class="p-6 space-y-5">
            {{-- Full Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Full Name
                </label>
                <input
                    wire:model.defer="name"
                    type="text"
                    class="w-full rounded-xl border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="e.g. Jane Wambua"
                >
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Relationship --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Relationship
                    </label>
                    <select
                        wire:model.defer="relationship"
                        class="w-full rounded-xl border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Select relationship</option>
                        <option value="parent">Parent</option>
                        <option value="sibling">Sibling</option>
                        <option value="child">Child</option>
                    </select>
                    @error('relationship')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DOB --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Date of Birth <span class="text-gray-400">(optional)</span>
                    </label>
                    <input
                        wire:model.defer="date_of_birth"
                        type="date"
                        class="w-full rounded-xl border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('date_of_birth')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-2 flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold
                           transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Add Dependent
                </button>
            </div>
        </form>
    </div>

    {{-- Dependents List --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Registered Dependents</h3>
                <p class="text-sm text-gray-500 mt-1">
                    View status and complete profiles.
                </p>
            </div>
            <p class="text-sm text-gray-500">
                {{ method_exists($dependents, 'total') ? $dependents->total() : $dependents->count() }} total
            </p>
        </div>

        <div class="divide-y">
            @forelse($dependents as $dependent)
                @php
                    $isDeceased = $dependent->status !== 'active';
                    $badgeClass = $dependent->status === 'active'
                        ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                        : 'bg-gray-100 text-gray-700 ring-gray-200';
                @endphp

                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-gray-50 transition">
                    <div class="min-w-0">
                        <p class="font-medium text-gray-900 truncate">
                            {{ $dependent->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ ucfirst($dependent->relationship) }}
                            @if($isDeceased)
                                <span class="text-gray-400">• Locked</span>
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-3 flex-wrap justify-end">
                        {{-- Status Badge --}}
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold ring-1 {{ $badgeClass }}">
                            {{ ucfirst($dependent->status) }}
                        </span>

                        {{-- Profile Completion --}}
                        @if(!$dependent->profile_completed)
                            <a
                                href="{{ route('dependents.profile', $dependent->id) }}"
                                class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Complete Profile
                            </a>
                        @else
                            <span class="text-emerald-700 font-semibold text-sm inline-flex items-center gap-2">
                                Complete <span aria-hidden="true">✅</span>
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-6 py-16 text-center space-y-3">
                    <div class="text-4xl">👨‍👩‍👧‍👦</div>
                    <div>
                        <p class="text-gray-800 font-semibold">No dependents yet</p>
                        <p class="text-gray-500 text-sm mt-1">
                            Add a dependent using the form above.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination Footer --}}
        @if(method_exists($dependents, 'links'))
            <div class="px-6 py-4 border-t">
                {{ $dependents->links() }}
            </div>
        @endif
    </div>

</div>

<div class="max-w-5xl mx-auto p-6 space-y-8">

    {{-- Page Header --}}
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">My Dependents</h2>
        <p class="text-sm text-gray-500">
            Manage your registered dependents. Deceased dependents are locked permanently.
        </p>
    </div>

    {{-- Add Dependent --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="text-lg font-medium text-gray-700 mb-4">Add Dependent</h3>

        <form wire:submit.prevent="addDependent" class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Full Name
                </label>
                <input
                    wire:model.defer="name"
                    type="text"
                    class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-100"
                    placeholder="e.g. Jane Wambua"
                >
            </div>
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Relationship
                    </label>
                    <select
                        wire:model.defer="relationship"
                        class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-100"
                    >
                        <option value="">Select relationship</option>
                        <option value="parent">Parent</option>
                        <option value="sibling">Sibling</option>
                        <option value="child">Child</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Date of Birth <span class="text-gray-400">(optional)</span>
                    </label>
                    <input
                        wire:model.defer="date_of_birth"
                        type="date"
                        class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-100"
                    >
                </div>
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md font-medium"
                >
                    Add Dependent
                </button>
            </div>
        </form>
    </div>

  {{-- Dependents List --}}
<div class="bg-white rounded-lg shadow-sm border divide-y">

    @forelse($dependents as $dependent)
        <div class="flex items-center justify-between p-4">
            <div>
                <p class="font-medium text-gray-800">
                    {{ $dependent->name }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ ucfirst($dependent->relationship) }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                {{-- Status Badge --}}
                <span class="px-3 py-1 text-sm rounded-full font-medium
                    {{ $dependent->status === 'active'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-200 text-gray-600'
                    }}">
                    {{ ucfirst($dependent->status) }}
                </span>

                {{-- Complete Profile Button --}}
                @if(!$dependent->profile_completed)
                    <a href="{{ route('dependents.profile', $dependent->id) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded font-medium transition">
                        Complete Profile
                    </a>
                @else
                    <span class="text-green-600 font-semibold text-sm">Complete ✅</span>
                @endif
            </div>
        </div>
    @empty
        <div class="p-6 text-center text-gray-500">
            No dependents added yet.
        </div>
    @endforelse

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $dependents->links() }}
    </div>

</div>


</div>

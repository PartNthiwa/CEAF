<div class="max-w-4xl mx-auto p-6 space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('member.dependents') }}"
           class="inline-flex items-center text-gray-600 hover:text-gray-800 hover:underline text-sm font-medium">
            <!-- Optional arrow icon -->
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dependents
        </a>
    </div>

    {{-- Page Header --}}
    <h2 class="text-2xl font-semibold text-gray-800">
        Complete Profile for {{ $dependent->name }}
    </h2>
    <p class="text-sm text-gray-500">
        Upload identification documents for your dependent. Once uploaded, the profile can be marked complete.
    </p>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- Upload Document --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="text-lg font-medium text-gray-700 mb-4">Upload Document</h3>
        <form wire:submit.prevent="uploadDocument" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Document Type</label>
                <input type="text" wire:model.defer="documentType"
                    class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-100"
                    placeholder="e.g. Birth Certificate, ID, Passport"
                >
                @error('documentType')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Select File</label>
                <input type="file" wire:model="file" class="w-full" />
                @error('file')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

                @if ($file)
                    <p class="text-sm text-gray-500 mt-1">Selected file: {{ $file->getClientOriginalName() }}</p>
                @endif
            </div>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                Upload
            </button>
        </form>
    </div>

    {{-- Uploaded Documents --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="text-lg font-medium text-gray-700 mb-4">Uploaded Documents</h3>

        @if($uploadedDocuments->count() > 0)
            <ul class="space-y-2">
                @foreach($uploadedDocuments as $doc)
                    <li class="flex justify-between items-center bg-gray-50 p-3 rounded">
                        <span>{{ $doc->type }}</span>
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                           class="text-blue-600 hover:underline text-sm">View</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 text-sm">No documents uploaded yet.</p>
        @endif
    </div>

    {{-- Mark Profile Complete --}}
    @if(!$dependent->profile_completed)
        <div class="flex justify-end">
            <button wire:click="markProfileComplete"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium">
                Mark Profile Complete
            </button>
        </div>
    @else
        <p class="text-green-700 font-semibold">Profile Completed ✅</p>
    @endif

</div>

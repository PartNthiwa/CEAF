<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 space-y-6">

    {{-- Back --}}
    <div>
        <a
            href="{{ route('member.dependents') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dependents
        </a>
    </div>

    {{-- Header --}}
    <div class="space-y-1">
        <h2 class="text-2xl font-bold text-gray-900">
            Complete Profile for {{ $dependent->name }}
        </h2>
        <p class="text-sm text-gray-600">
            Upload identification documents for your dependent. Once uploaded, you can mark the profile complete.
        </p>
    </div>

    {{-- Flash Messages --}}
    <div class="space-y-3">
        @if (session()->has('message'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                <p class="font-semibold">Success</p>
                <p class="text-sm mt-1">{{ session('message') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <p class="font-semibold">Something went wrong</p>
                <p class="text-sm mt-1">{{ session('error') }}</p>
            </div>
        @endif
    </div>

    {{-- Upload Document --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Upload Document</h3>
            <p class="text-sm text-gray-500 mt-1">
                Add a document type and upload the file.
            </p>
        </div>

        <form wire:submit.prevent="uploadDocument" class="p-6 space-y-5">
            {{-- Document Type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Document Type
                </label>
                <input
                    type="text"
                    wire:model.defer="documentType"
                    class="w-full rounded-xl border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="e.g. Birth Certificate, ID, Passport"
                >
                @error('documentType')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- File --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Select File
                </label>

                <div class="rounded-xl border border-gray-300 p-3">
                    <input type="file" wire:model="file" class="w-full text-sm" />
                </div>

                @error('file')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

                @if ($file)
                    <div class="mt-2 inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700">
                        <span class="font-semibold">Selected:</span>
                        <span>{{ $file->getClientOriginalName() }}</span>
                    </div>
                @endif

                {{-- Optional: upload progress (Livewire) --}}
                <div wire:loading wire:target="file" class="text-sm text-gray-500 mt-2">
                    Preparing file…
                </div>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold
                           transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    wire:loading.attr="disabled"
                    wire:target="uploadDocument,file"
                >
                    <span wire:loading.remove wire:target="uploadDocument,file">Upload</span>
                    <span wire:loading wire:target="uploadDocument,file">Uploading…</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Uploaded Documents --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Uploaded Documents</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Review uploaded files before completing the profile.
                </p>
            </div>
            <p class="text-sm text-gray-500">
                {{ $uploadedDocuments->count() }} total
            </p>
        </div>

        @if($uploadedDocuments->count() > 0)
            <div class="divide-y">
                @foreach($uploadedDocuments as $doc)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                        <div class="min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $doc->type }}</p>
                            <p class="text-sm text-gray-500 truncate">
                                {{ basename($doc->file_path) }}
                            </p>
                        </div>

                        <a
                            href="{{ asset('storage/' . $doc->file_path) }}"
                            target="_blank"
                            class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg
                                   bg-indigo-50 text-indigo-700 font-semibold text-sm hover:bg-indigo-100 transition"
                        >
                            View
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-14 text-center space-y-3">
                <div class="text-4xl">📄</div>
                <div>
                    <p class="text-gray-800 font-semibold">No documents yet</p>
                    <p class="text-gray-500 text-sm mt-1">
                        Upload at least one document to complete the profile.
                    </p>
                </div>
            </div>
        @endif
    </div>

    {{-- Mark Profile Complete --}}
    <div class="flex items-center justify-between">
        @if(!$dependent->profile_completed)
            <div class="text-sm text-gray-600">
                Status: <span class="font-semibold text-amber-700">Incomplete</span>
            </div>

            <button
                type="button"
                wire:click="markProfileComplete"
                class="inline-flex items-center justify-center px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold
                       transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                {{-- Optional guard: disable if no docs --}}
                {{-- @disabled($uploadedDocuments->count() === 0) --}}
            >
                Mark Profile Complete
            </button>
        @else
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 w-full">
                <p class="font-semibold">Profile Completed ✅</p>
                <p class="text-sm mt-1">This dependent’s profile is complete.</p>
            </div>
        @endif
    </div>

</div>

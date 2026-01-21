<x-app-layout>
    <x-slot name="header">
    <div class="flex items-center justify-between">

        {{-- Page Title --}}
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>

        {{-- Header Actions --}}
        <div class="flex items-center gap-3">

            <a href="{{ route('member.payments') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                Make Payment
            </a>

            <a href="{{ route('member.dependents') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium px-4 py-2 rounded-md">
                Dependents
            </a>

            <a href="{{ route('member.beneficiaries') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium px-4 py-2 rounded-md">
                Beneficiaries
            </a>

        </div>
    </div>
</x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

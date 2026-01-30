<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl mt-8 text-gray-800 leading-tight">
            Membership Pending Approval
        </h2>
    </x-slot>

    <section class="w-full flex items-center justify-center py-12">
        <div id="pending-approval"
             class="w-full max-w-6xl min-h-[70vh] bg-blue-600 rounded-xl shadow-xl flex items-center justify-center">

            <div class="max-w-7xl mx-auto px-6 text-center text-white">

                <h2 class="text-4xl md:text-5xl font-extrabold mb-3 relative inline-block">
                    Approval Pending
                    <span class="block mt-2 w-20 h-1 bg-yellow-400 rounded-full"></span>
                </h2>

                <p class="text-lg md:text-xl mb-12 max-w-3xl mx-auto text-white/90">
                    Your account has been created successfully, but membership access requires administrator approval.
                </p>

                <div class="bg-white p-10 rounded-2xl shadow-xl text-purple-700 max-w-lg w-full mx-auto">
                    <h3 class="text-2xl font-bold mb-4">What happens next?</h3>

                    <p class="text-gray-700 mb-6">
                        The administrator will review your registration and activate your membership.
                        Once approved, you will be able to access your dashboard and manage your profile.
                    </p>

                    <p class="text-sm text-gray-600">
                        Need help? Contact the administrator:
                        <strong class="text-purple-800">admin@ceaf.org</strong>
                    </p>

                    <div class="mt-6 space-y-3">
                        <a href="#"
                           class="inline-block w-full bg-yellow-400 text-purple-700 px-6 py-3 rounded-lg font-semibold  transition">
                            With deepest sympathy and respect
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-white border border-purple-200 text-purple-700 px-6 py-3 rounded-lg font-semibold hover:bg-red-200 transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <p class="text-md md:text-md font-semibold mt-8 text-white/90">
                    Carolina East Africa Foundation — Bereavement Registry <br> <small>&copy; {{ date('Y') }} {{ config('app.name', 'My App') }}. All rights reserved.
                </p></small>
                      
            </div>
        </div>
    </section>
</x-app-layout>

<footer class="bg-gray-800 text-white text-center py-4">
    <div class="text-sm"> Carolina East Africa Foundation Benevolent Fund Management System    &copy; {{ date('Y') }} </div>
</footer>

<footer class="bg-gray-300 text-black-900 border-t ">
    <div class="max-w-7xl mx-auto px-6 py-6 flex flex-col md:flex-row justify-between items-center">

        {{-- Left Side --}}
        <div class="text-sm text-black-900">
            &copy; iHub Softwares {{ date('Y') }} {{ config('app.name', 'My App') }}. All rights reserved.
        </div>

        {{-- Right Side / Links --}}
        <div class="flex flex-wrap gap-4 mt-4 md:mt-0">
            <a href="#" class="hover:text-gray-900 text-sm">Privacy Policy</a>
            <a href="#" class="hover:text-gray-900 text-sm">Terms of Service</a>
            <a href="#" class="hover:text-gray-900 text-sm">Help</a>
        </div>

    </div>
</footer>

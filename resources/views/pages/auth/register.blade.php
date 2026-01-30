<x-layouts::auth>
    <section id="register" class="w-full bg-blue-600 py-24">
        <div class="max-w-7xl mx-auto px-6 text-center text-white">
            
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('path-to-your-logo.png') }}" alt="Carolina East Africa Foundation" class="mx-auto h-12">
            </div>

            <!-- Section Heading -->
            <h2 class="text-4xl md:text-5xl font-extrabold mb-1 relative inline-block">
                Become a Member Today
                <span class="block mt-2 w-20 h-1 bg-yellow-400 rounded-full"></span>
            </h2>

            <p class="text-lg md:text-xl mb-16 max-w-3xl mx-auto text-white/90">
                Join our community to support and be part of the Carolina East Africa Foundation’s mission. Fill out your details below to get started!
            </p>

            <!-- Registration Form -->
            <div class="bg-white p-8 rounded-lg shadow-lg text-purple-700 max-w-lg mx-auto">
                <h3 class="text-2xl font-bold mb-6">Create Your Account</h3>
                @if(!request('token'))
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-3 mb-4">
                        Registration requires an invitation link.
                    </div>
                @endif

                @error('token')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror

                <form method="POST" action="{{ route('register.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Session Status -->
                    <x-auth-session-status class="text-center mb-4" :status="session('status')" />
                    <input type="hidden" name="token" value="{{ request('token') }}">

                    <!-- Name Input -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-purple-700">{{ __('Full Name') }}</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            required
                            class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                            placeholder="{{ __('Enter your full name') }}"
                            value="{{ old('name') }}"
                        />
                    </div>

                    <!-- Email Address Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-purple-700">{{ __('Email Address') }}</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                            placeholder="email@example.com"
                            value="{{ old('email') }}"
                        />
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-purple-700">{{ __('Password') }}</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                            placeholder="{{ __('Enter your password') }}"
                        />
                    </div>

                    <!-- Confirm Password Input -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-purple-700">{{ __('Confirm Password') }}</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            class="w-full px-4 py-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                            placeholder="{{ __('Re-enter your password') }}"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            class="w-full bg-yellow-400 text-purple-700 px-8 py-3 rounded-lg font-semibold hover:bg-yellow-300 hover:scale-105 transform transition duration-300"
                        >
                            {{ __('Create Account') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Return to Website Link -->
            <div class="flex justify-center mt-4">
                <a href="{{ url('/') }}" class="text-sm text-zinc-600 hover:text-zinc-800 font-semibold">
                    {{ __('Return to website') }}
                </a>
            </div>

            <!-- Login Link -->
            <div class="text-center text-sm text-zinc-600 mt-6">
                <span>{{ __('Already have an account?') }}</span>
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-800">
                    {{ __('Log in') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Custom Styling -->
    <style>
        .input-field {
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: #f59e0b;
            outline: none;
        }

        .input-field::placeholder {
            color: #6b7280;
        }

        .input-field:focus::placeholder {
            color: #f59e0b;
        }

        .input-field.error {
            border-color: #ef4444;
            background-color: #fee2e2;
        }

        .input-field.error::placeholder {
            color: #ef4444;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: -8px;
            margin-bottom: 12px;
            text-align: left;
        }

        .error-message span {
            font-weight: bold;
        }
    </style>
</x-layouts::auth>

@extends('layouts.fullscreen-layout')

@section('content')
<div class="relative z-1 bg-white p-6 sm:p-0 dark:bg-gray-900">
    <div class="flex h-screen w-full flex-col justify-center sm:p-0 lg:flex-row dark:bg-gray-900">
        <!-- Form -->
        <div class="flex w-full flex-1 flex-col lg:w-1/2">
            <div class="mx-auto w-full max-w-md pt-5 sm:py-10">
                <a href="/" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    Back to dashboard
                </a>
            </div>
            <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">
                <div class="mb-5 sm:mb-8">
                    <h1 class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">Sign Up</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Enter your details to create your account!
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 text-red-500">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('signup') }}">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Username<span class="text-error-500">*</span></label>
                            <input type="text" name="username" placeholder="Enter your username" value="{{ old('username') }}" required
                                class="h-11 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-none focus:border-brand-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                            <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}"
                                class="h-11 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-none focus:border-brand-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password<span class="text-error-500">*</span></label>
                            <input type="password" name="password" placeholder="Enter your password" required
                                class="h-11 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-none focus:border-brand-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Confirm Password<span class="text-error-500">*</span></label>
                            <input type="password" name="password_confirmation" placeholder="Confirm your password" required
                                class="h-11 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-none focus:border-brand-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <div>
                            <label class="flex items-center text-sm text-gray-700 dark:text-gray-400">
                                <input type="checkbox" required class="mr-2">
                                I agree to the <a href="#" class="text-blue-500">Terms and Conditions</a>
                            </label>
                        </div>

                        <div>
                            <button type="submit" class="bg-brand-500 hover:bg-brand-600 w-full rounded-lg px-4 py-3 text-white font-medium transition">
                                Sign Up
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-5 text-center text-sm text-gray-700 dark:text-gray-400">
                    Already have an account? <a href="/login" class="text-brand-500 hover:text-brand-600">Sign In</a>
                </div>
            </div>
        </div>

        <!-- Optional Right Side Graphic -->
        <div class="bg-brand-950 hidden lg:block lg:w-1/2 dark:bg-white/5"></div>
    </div>
</div>
@endsection
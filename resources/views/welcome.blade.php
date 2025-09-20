<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LT HR Management</title>

    {{-- Load Tailwind + JS via Vite (Breeze default) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900">

    <main class="min-h-screen flex items-center justify-center p-6">
        <div class="max-w-3xl w-full">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-100 mb-6">
                    <!-- simple logo placeholder -->
                    <span class="text-2xl font-extrabold text-indigo-600">LT</span>
                </div>

                <h1 class="text-4xl font-extrabold tracking-tight text-indigo-700">
                    LT HR Management
                </h1>
                <p class="mt-3 text-lg text-gray-600">
                    Simple, fast, and reliable HR for your team.
                </p>
            </div>

            <div class="mt-10 grid sm:grid-cols-2 gap-4 max-w-md mx-auto">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="inline-flex items-center justify-center rounded-xl px-6 py-3 font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        Go to Dashboard
                    </a>
                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center justify-center rounded-xl px-6 py-3 font-semibold border border-indigo-600 text-indigo-700 hover:bg-indigo-50 transition">
                        Profile
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center rounded-xl px-6 py-3 font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        Log In
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center rounded-xl px-6 py-3 font-semibold border border-indigo-600 text-indigo-700 hover:bg-indigo-50 transition">
                        Register
                    </a>
                @endauth
            </div>

            <p class="mt-12 text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} LT HR Management. All rights reserved.
            </p>
        </div>
    </main>

</body>
</html>

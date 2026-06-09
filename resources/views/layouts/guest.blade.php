<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CIT Payment System') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('storage/logofavicon/favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans text-white antialiased bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 min-h-screen overflow-x-hidden">
        <div class="min-h-screen flex flex-col items-center justify-center px-6 py-12">

            <!-- Logo & Branding -->
            <div class="flex flex-col items-center mb-10">
                <img
                    src="{{ asset('storage/shieldfavicon/shield3.png') }}"
                    alt="CIT Logo"
                    class="h-20 w-20 object-contain drop-shadow-2xl mb-4"
                >
                <h1 class="text-3xl font-bold tracking-tight text-white">
                    CIT Payment System
                </h1>
                <p class="text-blue-300 text-sm mt-1">Capellan Institute of Technology</p>
            </div>

            <!-- Main Content Card -->
            <div class="w-full sm:max-w-md bg-white/10 backdrop-blur-2xl border border-white/10 rounded-3xl shadow-2xl overflow-hidden">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="text-center text-blue-300/60 text-sm mt-8">
                &copy; {{ date('Y') }} Capellan Institute of Technology. All Rights Reserved.
            </p>
        </div>
    </body>
</html>

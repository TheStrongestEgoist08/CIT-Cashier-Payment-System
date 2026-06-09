<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/icons/CITLOGOV1.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 font-sans overflow-x-hidden">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/10 backdrop-blur-xl border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img
                    src="{{ asset('storage/shieldfavicon/shield3.png') }}"
                    alt="CIT Logo"
                    class="h-12 w-12 object-contain drop-shadow-lg"
                >
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">
                        CIT Payment System
                    </h1>
                    <p class="text-xs text-blue-300 -mt-1">Capellan Institute of Technology</p>
                </div>
            </div>

            @if (Auth::check())
                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="px-6 py-3 bg-white text-blue-950 font-semibold rounded-xl hover:bg-yellow-300 hover:text-blue-950 transition-all duration-200 shadow-lg">
                        Admin Dashboard
                    </a>
                @elseif (Auth::user()->role === 'cashier')
                    <a href="{{ route('cashier.summary') }}"
                       class="px-6 py-3 bg-white text-blue-950 font-semibold rounded-xl hover:bg-yellow-300 hover:text-blue-950 transition-all duration-200 shadow-lg">
                        Cashier Dashboard
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="px-6 py-3 bg-white text-blue-950 font-semibold rounded-xl hover:bg-yellow-300 hover:text-blue-950 transition-all duration-200 shadow-lg">
                    Login
                </a>
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-1 flex items-center justify-center px-6 py-16 relative">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 lg:gap-16 items-center">

            <!-- Left Content -->
            <div class="space-y-8 text-white">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full border border-white/20">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium">Official Payment Portal</span>
                </div>

                <h2 class="text-5xl md:text-6xl lg:text-7xl font-bold leading-tight tracking-tighter">
                    Welcome to<br>
                    <span class="bg-gradient-to-r from-yellow-300 to-amber-300 bg-clip-text text-transparent">
                        Capellan Institute of Technology
                    </span>
                </h2>

                <p class="text-xl text-blue-100 max-w-lg">
                    Streamlined, secure, and efficient payment management for tuition,
                    enrollment, uniforms, and other school fees.
                </p>

                <div class="flex flex-wrap gap-4 pt-4">
                    @if (Auth::check())
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="px-8 py-6 bg-white text-blue-950 font-semibold text-lg rounded-2xl hover:bg-yellow-300 hover:text-blue-950 transition-all duration-300 shadow-xl inline-block">
                                Access System
                            </a>
                        @elseif (Auth::user()->role === 'cashier')
                            <a href="{{ route('cashier.summary') }}"
                               class="px-8 py-6 bg-white text-blue-950 font-semibold text-lg rounded-2xl hover:bg-yellow-300 hover:text-blue-950 transition-all duration-300 shadow-xl inline-block">
                                Access System
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="px-8 py-6 bg-white text-blue-950 font-semibold text-lg rounded-2xl hover:bg-yellow-300 hover:text-blue-950 transition-all duration-300 shadow-xl inline-block">
                            Login to Continue
                        </a>
                    @endif
                </div>

                <!-- Trust Indicators -->
                <div class="flex items-center gap-8 pt-6 text-sm text-blue-200">
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400">✔</span>
                        Secure Payments
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400">✔</span>
                        Real-time Tracking
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400">✔</span>
                        24/7 Access
                    </div>
                </div>
            </div>

            <!-- Right Image -->
            <div class="relative flex justify-center">
                <div class="absolute -inset-4 bg-gradient-to-br from-yellow-400/20 to-transparent rounded-[3rem] -rotate-3"></div>
                <img
                    src="{{ asset('storage/backgrounds/CITBUILDING.png') }}"
                    alt="CIT Campus"
                    class="relative rounded-3xl shadow-2xl w-full max-w-lg lg:max-w-xl object-cover border border-white/20"
                >
                <!-- Decorative Overlay -->
                <div class="absolute -bottom-6 -right-6 bg-white/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20 shadow-xl">
                    <p class="text-white text-sm font-medium">Est. 1991</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-black/30 backdrop-blur-md border-t border-white/10 py-8 text-center text-white/70">
        <div class="max-w-7xl mx-auto px-6">
            <p class="text-sm">
                &copy; {{ date('Y') }} Capellan Institute of Technology. All Rights Reserved.
            </p>
            <p class="text-xs mt-2 text-white/50">
                Secure • Reliable • Student-Centered
            </p>
        </div>
    </footer>

</body>
</html>

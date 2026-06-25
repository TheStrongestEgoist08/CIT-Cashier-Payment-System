<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                @php
                    $userRole = Auth::user()->role;
                @endphp

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if($userRole === 'admin')
                        <x-nav-link :href="route('payables')" :active="request()->routeIs('payables')">
                            {{ __('Payables') }}
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('students')" :active="request()->routeIs('students')">
                        {{ __('Students') }}
                    </x-nav-link>

                    <x-nav-link :href="route('transactions')" :active="request()->routeIs('transactions')">
                        {{ __('Transactions') }}
                    </x-nav-link>

                    <x-nav-link :href="route('summary')" :active="request()->routeIs('summary')">
                        {{ __('SOA') }}
                    </x-nav-link>

                    <x-nav-link :href="route('reports')" :active="request()->routeIs('reports')">
                        {{ __('Reports') }}
                    </x-nav-link>

                    @if($userRole === 'admin')
                        <x-nav-link :href="route('accounts')" :active="request()->routeIs('accounts')">
                            {{ __('Accounts') }}
                        </x-nav-link>
                    @endif

                    @if($userRole === 'admin')
                        <x-nav-link :href="route('activities')" :active="request()->routeIs('activities')">
                            {{ __('Logs') }}
                        </x-nav-link>
                    @endif

                    @if($userRole === 'admin')
                        <x-nav-link :href="route('backup')" :active="request()->routeIs('backup')">
                            {{ __('Backup') }}
                        </x-nav-link>
                    @endif

                    @if($userRole === 'cashier')
                        <x-nav-link :href="route('OriginalReceipt')" :active="request()->routeIs('OriginalReceipt')">
                            {{ __('OR') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- Modern Toast Notifications --}}
<div id="toast-container"
     class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 items-end max-w-sm w-full pointer-events-none">
</div>

<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const accent = type === 'success' ? 'emerald' : 'red';
    const progressColor = type === 'success' ? 'emerald-600' : 'red-600';

    const toast = document.createElement('div');
    toast.className = `toast group bg-white border border-gray-100 shadow-2xl
                       flex items-center gap-4 px-5 py-4 rounded-2xl w-full max-w-xs
                       pointer-events-auto relative overflow-hidden`;

    toast.innerHTML = `
        <!-- Bell Icon -->
        <div class="flex-shrink-0">
            <div class="w-9 h-9 rounded-2xl bg-${accent}-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell-fill text-${accent}-600" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                </svg>
            </div>
        </div>

        <!-- Message -->
        <div class="flex-1 text-[15px] leading-tight font-medium text-gray-800 pr-3">
            ${message}
        </div>

        <!-- Close Button -->
        <button onclick="dismissToast(this)"
                class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-all text-3xl leading-none">
            ×
        </button>

        <!-- Progress Bar - For Both Success & Error -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-100 rounded-b-2xl overflow-hidden">
            <div class="progress h-full bg-${progressColor} w-full origin-left rounded-b-2xl"></div>
        </div>
    `;

    container.appendChild(toast);

    // Entrance animation
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    }, 10);

    // Auto dismiss + progress for both types
    const progress = toast.querySelector('.progress');
    if (progress) {
        progress.style.transition = 'width 4.8s linear';
        setTimeout(() => progress.style.width = '0%', 50);
    }

    setTimeout(() => dismissToast(toast), 4800);
}

function dismissToast(el) {
    const toast = el.closest('.toast') || el;
    toast.style.transition = 'all 0.35s cubic-bezier(0.4, 0, 0.2, 1)';
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100px) scale(0.95)';

    setTimeout(() => toast.remove(), 350);
}

// Show Laravel Session Messages
document.addEventListener('DOMContentLoaded', () => {
    @if (session('success'))
        showToast("{{ addslashes(session('success')) }}", 'success');
    @endif

    @if (session('error'))
        showToast("{{ addslashes(session('error')) }}", 'error');
    @endif
});
</script>

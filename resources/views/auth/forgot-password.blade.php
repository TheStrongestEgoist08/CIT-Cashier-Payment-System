<x-guest-layout>
    <div class="px-8 py-10">
        <div class="mb-6 text-blue-200 text-sm leading-relaxed">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-white" />
                <x-text-input
                    id="email"
                    class="block mt-1 w-full bg-white/10 border border-white/20 text-white placeholder:text-blue-300 focus:border-yellow-300 focus:ring-yellow-300 rounded-2xl"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-8">
                <x-primary-button class="px-8 py-3.5 text-base font-semibold bg-yellow-300 hover:bg-amber-300 text-blue-950 rounded-2xl">
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>

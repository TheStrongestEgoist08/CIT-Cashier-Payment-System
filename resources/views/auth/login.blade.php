<x-guest-layout>
    <div class="px-8 py-10">

        <!-- Session Status - Now properly positioned inside the card with good spacing -->
        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
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
                    autocomplete="username"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-6">
                <x-input-label for="password" :value="__('Password')" class="text-white" />
                <x-text-input
                    id="password"
                    class="block mt-1 w-full bg-white/10 border border-white/20 text-white placeholder:text-blue-300 focus:border-yellow-300 focus:ring-yellow-300 rounded-2xl"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-6">
                <label for="remember_me" class="inline-flex items-center">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="rounded border-white/30 bg-white/10 text-yellow-400 focus:ring-yellow-300"
                        name="remember"
                    >
                    <span class="ms-2 text-sm text-blue-200">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-8">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-blue-300 hover:text-white transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ms-4 px-8 py-3.5 text-base font-semibold bg-yellow-300 hover:bg-amber-300 text-blue-950 rounded-2xl">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>

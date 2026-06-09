<x-guest-layout>
    <div class="px-8 py-10">
        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-white" />
                <x-text-input
                    id="email"
                    class="block mt-1 w-full bg-white/10 border border-white/20 text-white placeholder:text-blue-300 focus:border-yellow-300 focus:ring-yellow-300 rounded-2xl"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
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
                    autocomplete="new-password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-6">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-white" />

                <x-text-input
                    id="password_confirmation"
                    class="block mt-1 w-full bg-white/10 border border-white/20 text-white placeholder:text-blue-300 focus:border-yellow-300 focus:ring-yellow-300 rounded-2xl"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-8">
                <x-primary-button class="px-8 py-3.5 text-base font-semibold bg-yellow-300 hover:bg-amber-300 text-blue-950 rounded-2xl">
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>

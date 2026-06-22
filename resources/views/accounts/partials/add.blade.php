<!-- Add Account Modal -->
<div x-show="showAddModal"
     class="fixed inset-0 z-[100] flex items-center justify-center p-4"
     style="display: none;">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500/75"
         @click="showAddModal = false"></div>

    <!-- Modal -->
    <div class="relative z-[110] bg-white rounded-3xl shadow-2xl w-full max-w-lg h-[90vh] flex flex-col">

        <form method="POST" action="{{ route('accounts.store') }}" class="flex flex-col h-full">
            @csrf

            <!-- Header -->
            <div class="px-8 py-6 border-b flex-shrink-0">
                <div class="flex justify-between items-center">
                    <h3 class="text-2xl font-semibold text-gray-900">Add New Account</h3>
                    <button type="button"
                            @click="showAddModal = false"
                            class="text-4xl leading-none text-gray-300 hover:text-gray-500">
                        ×
                    </button>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 min-h-0 overflow-y-auto px-8 pt-6 pb-8">
                <div class="space-y-6">

                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               class="w-full px-5 py-4 border @error('name') border-red-500 @enderror border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500">
                        @error('name')
                            <div class="mt-2 flex items-start gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-3 py-2">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 mt-0.5 flex-shrink-0"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="leading-tight">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="w-full px-5 py-4 border @error('email') border-red-500 @enderror border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500">
                        @error('email')
                            <div class="mt-2 flex items-start gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-3 py-2">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 mt-0.5 flex-shrink-0"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="leading-tight">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password"
                               name="password"
                               required
                               class="w-full px-5 py-4 border @error('password') border-red-500 @enderror border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500"
                               minlength="8" maxlength="25">
                        @error('password')
                            <div class="mt-2 flex items-start gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-3 py-2">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 mt-0.5 flex-shrink-0"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="leading-tight">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password"
                               name="password_confirmation"
                               required
                               class="w-full px-5 py-4 border @error('password_confirmation') border-red-500 @enderror border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500"
                               minlength="8" maxlength="25">
                        @error('password_confirmation')
                            <div class="mt-2 flex items-start gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-3 py-2">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 mt-0.5 flex-shrink-0"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="leading-tight">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select name="role"
                                required
                                class="w-full px-5 py-4 border @error('role') border-red-500 @enderror border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select Role</option>
                            <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <div class="mt-2 flex items-start gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-3 py-2">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 mt-0.5 flex-shrink-0"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="leading-tight">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-6 border-t flex-shrink-0 flex justify-end gap-4">
                <button type="button"
                        @click="showAddModal = false"
                        class="px-8 py-3 text-gray-700 hover:bg-gray-100 rounded-2xl font-medium">
                    Cancel
                </button>

                <button type="submit"
                        class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-semibold hover:bg-indigo-700">
                    Create Account
                </button>
            </div>
        </form>
    </div>
</div>

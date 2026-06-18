<!-- Add Account Modal -->
<div x-show="showAddModal"
     class="fixed inset-0 z-[100] flex items-center justify-center p-4"
     style="display: none;">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500/75"
         @click="showAddModal = false"></div>

    <!-- Modal -->
    <div class="relative z-[110] bg-white rounded-3xl shadow-2xl w-full max-w-lg h-[90vh] flex flex-col">

        <form
            method="POST"
            action="{{ route('accounts.store') }}"
            class="flex flex-col h-full"
        >
            @csrf

            <!-- Header -->
            <div class="px-8 py-6 border-b flex-shrink-0">
                <div class="flex justify-between items-center">
                    <h3 class="text-2xl font-semibold text-gray-900">
                        Add New Account
                    </h3>
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name
                        </label>
                        <input type="text" name="name" required
                               class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <input type="email" name="email" required
                               class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500"
                            minlength="8" maxlength="25"
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,25}$"
                            title="Password must be 8-25 characters long, contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character."
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            name="password_confirmation" required
                            class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500"
                            minlength="8" maxlength="25"
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,25}$"
                            title="Password must be 8-25 characters long, contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character."
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Role
                        </label>
                        <select name="role" required
                                class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500">
                            <option value="cashier">Cashier</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <!-- Remove the h-96 test div in production -->
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

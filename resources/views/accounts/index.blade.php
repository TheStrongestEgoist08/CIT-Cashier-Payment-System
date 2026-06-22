
{{-- Accounts --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Accounts Management') }}
        </h2>
    </x-slot>

    <div class="py-10"
         x-data="{
            showAddModal: @js($errors->any()),
            
            showActivateModal: false,
            showDeactivateModal: false,
            showDeleteModal: false,

            userToActivate: { id: null, name: '' },
            userToDeactivate: { id: null, name: '' },
            userToDelete: { id: null, name: '' },

            activateUrl: '',
            deactivateUrl: '',
            deleteUrl: '',

            confirmActivate(id, name) {
                this.userToActivate = { id, name };
                this.activateUrl = `{{ url('/accounts') }}/${id}/activate`;
                this.showActivateModal = true;
            },

            confirmDeactivate(id, name) {
                this.userToDeactivate = { id, name };
                this.deactivateUrl = `{{ url('/accounts') }}/${id}/deactivate`;
                this.showDeactivateModal = true;
            },

            confirmDelete(id, name) {
                this.userToDelete = { id, name };
                this.deleteUrl = `{{ url('/accounts') }}/${id}`;
                this.showDeleteModal = true;
            }
         }">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filters -->
            <div class="bg-white shadow-sm rounded-3xl p-6 mb-8">
                <form method="GET" class="flex flex-wrap gap-4 items-center">
                    <!-- Search -->
                    <div class="flex-1 min-w-[260px]">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by name..."
                            class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Role -->
                    <div class="w-full sm:w-48">
                        <select name="role"
                                class="w-full bg-white border border-gray-200 rounded-2xl py-3.5 px-5 focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="cashier" {{ request('role') === 'cashier' ? 'selected' : '' }}>Cashier</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="w-full sm:w-48">
                        <select name="status"
                                class="w-full bg-white border border-gray-200 rounded-2xl py-3.5 px-5 focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-semibold transition">
                            Filter
                        </button>

                        <a href="{{ route('accounts') }}"
                           class="px-8 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-medium transition">
                            Clear
                        </a>

                        <button type="button" @click="showAddModal = true"
                                class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-semibold flex items-center gap-2 transition">
                            <span class="text-xl leading-none">+</span>
                            Add Account
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white shadow-sm rounded-3xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-8 py-5 text-left text-sm font-semibold text-gray-600">NAME</th>
                                <th class="px-8 py-5 text-left text-sm font-semibold text-gray-600">EMAIL</th>
                                <th class="px-8 py-5 text-left text-sm font-semibold text-gray-600">ROLE</th>
                                <th class="px-8 py-5 text-left text-sm font-semibold text-gray-600">STATUS</th>
                                <th class="px-8 py-5 text-right text-sm font-semibold text-gray-600">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-6 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-8 py-6 text-gray-600">{{ $user->email }}</td>
                                <td class="px-8 py-6">
                                    <span class="inline-flex px-4 py-1.5 rounded-2xl text-sm font-medium
                                        {{ $user->role === 'admin' ? 'bg-violet-100 text-violet-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="inline-flex px-4 py-1.5 rounded-2xl text-sm font-medium
                                        {{ $user->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-2">
                                        @if($user->id !== auth()->id())
                                            @if($user->status === 'inactive')
                                                <button @click="confirmActivate({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                        class="px-5 py-2.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-2xl text-sm font-medium transition">
                                                    Activate
                                                </button>
                                            @else
                                                <button @click="confirmDeactivate({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                        class="px-5 py-2.5 bg-amber-100 hover:bg-amber-200 text-amber-700 rounded-2xl text-sm font-medium transition">
                                                    Deactivate
                                                </button>
                                            @endif
                                            <button @click="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                    class="px-5 py-2.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-2xl text-sm font-medium transition">
                                                Delete
                                            </button>
                                        @else
                                            <span class="text-gray-400 text-sm italic">Current User</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-16 text-center text-gray-500">No accounts found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{ $users->appends(request()->query())->links() }}

        </div>

        <!-- Modals -->
        @include('accounts.partials.add')
        @include('accounts.partials.activate')
        @include('accounts.partials.deactivate')
        @include('accounts.partials.delete')
    </div>
</x-app-layout>

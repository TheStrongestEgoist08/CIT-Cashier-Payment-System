{{-- OR Page --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Original Receipt Management') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Messages -->
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Original Receipt</h3>

                        {{-- Show Create button ONLY if no record exists --}}
                        @if (!$original_receipt)
                            <button @click="showCreateModal()"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                                + New Original Receipt
                            </button>
                        @endif
                    </div>

                    @if ($original_receipt)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Receipt ID</p>
                                    <p class="text-xl font-bold text-gray-800">{{ $original_receipt->original_receipt_id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Created At</p>
                                    <p class="text-gray-700">{{ $original_receipt->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>

                            <div class="mt-6 flex gap-3">
                                <button @click="showEditModal({{ $original_receipt }})"
                                        class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2 rounded-lg transition">
                                    Edit
                                </button>
                                <button @click="showDeleteModal({{ $original_receipt->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg transition">
                                    Delete
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <p class="text-gray-500 text-lg">No Original Receipt found.</p>
                            <p class="text-gray-400 mt-2">Click the button above to create one.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @if (!$original_receipt)
        @include('OR.partials.modals.or-create')
    @endif

    @include('OR.partials.modals.or-edit')
    @include('OR.partials.modals.or-delete-confirm')

</x-app-layout>

<script>
    function initORModals() {
        return {
            showCreate: false,
            showEdit: false,
            showDelete: false,
            editData: {},
            deleteId: null,

            showCreateModal() {
                this.showCreate = true;
            },

            showEditModal(receipt) {
                this.editData = { ...receipt };
                this.showEdit = true;
            },

            showDeleteModal(id) {
                this.deleteId = id;
                this.showDelete = true;
            }
        }
    }
</script>

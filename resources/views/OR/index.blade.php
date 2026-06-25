{{-- OR Page --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Original Receipt') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100"
         x-data="{
            showCreate: false,
            showEdit: false,
            showDelete: false,
            editData: {},
            deleteId: null,

            showCreateModal() { this.showCreate = true; },
            showEditModal(receipt) {
                this.editData = { ...receipt };
                this.showEdit = true;
            },
            showDeleteModal(id) {
                this.deleteId = id;
                this.showDelete = true;
            }
         }">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg" style="min-height: 460px;">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-end items-center mb-6">
                        @if (!$original_receipt)
                            <button @click="showCreateModal()"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-lg transition text-base font-semibold">
                                + New Original Receipt
                            </button>
                        @endif
                    </div>

                    @if ($original_receipt)
                        <div class="max-w-md mx-auto text-center pt-2 pb-4">
                            <!-- Blue Header like in image -->
                            <p class="inline-block bg-blue-600 text-white text-base font-medium px-6 py-2 rounded-lg mb-8">
                                Your Current Original Receipt ID is:
                            </p>

                            <!-- Bigger Digit Boxes -->
                            <div class="flex justify-center gap-3 mb-10">
                                @php
                                    $digits = str_split((string) $original_receipt->original_receipt_id);
                                @endphp
                                @foreach ($digits as $digit)
                                    <div class="w-16 h-20 bg-white border-2 border-gray-200 rounded-2xl flex items-center justify-center text-6xl font-bold text-gray-900 shadow-sm">
                                        {{ $digit }}
                                    </div>
                                @endforeach
                            </div>

                            <!-- Bigger Action Buttons -->
                            <div class="flex gap-4 justify-center">
                                <button @click="showEditModal({{ $original_receipt }})"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 rounded-2xl transition flex items-center justify-center gap-2 text-base shadow-sm">
                                    ✏️ Edit
                                </button>
                                <button @click="showDeleteModal({{ $original_receipt->id }})"
                                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-4 rounded-2xl transition text-base shadow-sm">
                                    Delete
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-xl">No Original Receipt found.</p>
                            <p class="text-gray-400 mt-2 text-base">Click the button above to create one.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        @if (!$original_receipt)
            @include('OR.partials.modals.or-create')
        @endif
        @include('OR.partials.modals.or-edit')
        @include('OR.partials.modals.or-delete-confirm')

    </div>
</x-app-layout>

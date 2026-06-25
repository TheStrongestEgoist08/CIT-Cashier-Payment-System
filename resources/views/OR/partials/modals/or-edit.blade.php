
<!-- Edit Modal -->
<div x-cloak
     x-show="showEdit"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
     style="display: none;">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">

        <!-- Header -->
        <div class="bg-blue-600 px-6 py-5 text-white">
            <h3 class="text-2xl font-semibold text-center">Edit Original Receipt</h3>
        </div>

        <!-- Body -->
        <div class="p-8">
            <form :action="`/OriginalReceipt/update/${editData.id}`" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-3 text-center">
                        Original Receipt ID
                    </label>
                    <input type="number"
                           name="original_receipt_id"
                           :value="editData.original_receipt_id"
                           class="w-full text-center text-4xl font-bold border border-gray-300 rounded-xl px-6 py-5 focus:ring-4 focus:ring-amber-200 focus:border-amber-500 outline-none transition-all"
                           placeholder="000000"
                           required>
                </div>

                <div class="flex justify-end gap-4">
                    <button type="button"
                            @click="showEdit = false"
                            class="px-8 py-3 text-gray-600 hover:bg-gray-100 font-medium rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-md">
                        Update Receipt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

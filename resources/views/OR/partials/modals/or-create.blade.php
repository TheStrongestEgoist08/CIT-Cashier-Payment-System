
<!-- Create Modal -->
<div x-data="initORModals()" class="fixed inset-0 z-50" style="display: none" :style="{ display: showCreate ? 'flex' : 'none' }">
    <div class="fixed inset-0 bg-black/50"></div>

    <div class="relative m-auto bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-xl font-semibold mb-4">Create New Original Receipt</h3>

        <form action="{{ route('or.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Original Receipt ID</label>
                <input type="number"
                       name="original_receipt_id"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                       required>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="showCreate = false"
                        class="px-5 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    Cancel
                </button>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

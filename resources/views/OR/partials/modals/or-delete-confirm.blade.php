
<!-- Delete Confirmation Modal -->
<div x-cloak
     x-show="showDelete"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
     style="display: none;">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative">
        <h3 class="text-xl font-semibold text-red-600 mb-2">Delete Confirmation</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this Original Receipt? This action cannot be undone.</p>

        <form :action="`/OriginalReceipt/delete/${deleteId}`" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end gap-3">
                <button type="button"
                        @click="showDelete = false"
                        class="px-5 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    Cancel
                </button>
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">
                    Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>

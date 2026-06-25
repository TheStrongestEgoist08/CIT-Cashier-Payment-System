
<!-- Delete Confirmation Modal -->
<div x-cloak
     x-show="showDelete"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
     style="display: none;">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">

        <!-- Header -->
        <div class="bg-red-600 px-6 py-5 text-white">
            <h3 class="text-2xl font-semibold text-center">Delete Original Receipt</h3>
        </div>

        <!-- Body -->
        <div class="p-8">
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mb-4">
                    <span class="text-3xl">⚠️</span>
                </div>
                <h4 class="text-xl font-semibold text-gray-800 mb-2">Are you sure?</h4>
                <p class="text-gray-600">
                    You are about to delete this Original Receipt.<br>
                    This action cannot be undone.
                </p>
            </div>

            <form :action="`/OriginalReceipt/delete/${deleteId}`" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-end gap-4">
                    <button type="button"
                            @click="showDelete = false"
                            class="px-8 py-3 text-gray-600 hover:bg-gray-100 font-medium rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition shadow-md">
                        Yes, Delete Receipt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

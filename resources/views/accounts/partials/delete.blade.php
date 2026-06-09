<!-- Delete Modal -->
<div x-show="showDeleteModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showDeleteModal = false"></div>

    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 relative z-[110]">
        <h3 class="text-2xl font-semibold text-red-600 mb-4">Delete Account</h3>
        <p class="text-gray-600 text-lg">
            Are you sure you want to permanently delete <strong x-text="userToDelete.name"></strong>?
        </p>
        <p class="text-red-500 text-sm mt-1">This action cannot be undone.</p>

        <div class="mt-10 flex justify-end gap-4">
            <button @click="showDeleteModal = false"
                    class="px-8 py-3 text-gray-700 hover:bg-gray-100 rounded-2xl font-medium">Cancel</button>
            <form :action="deleteUrl" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-semibold">
                    Yes, Delete
                </button>
            </form>
        </div>
    </div>
</div>

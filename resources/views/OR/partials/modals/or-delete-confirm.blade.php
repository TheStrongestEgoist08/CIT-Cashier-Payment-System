
<!-- Delete Confirmation Modal -->
<div x-data="initORModals()" class="fixed inset-0 z-50" style="display: none" :style="{ display: showDelete ? 'flex' : 'none' }">
    <div class="fixed inset-0 bg-black/50"></div>

    <div class="relative m-auto bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-xl font-semibold text-red-600 mb-2">Delete Confirmation</h3>
        <p class="text-gray-600">Are you sure you want to delete this Original Receipt? This action cannot be undone.</p>

        <form action="{{ route('or.delete', '') }}" method="POST" class="mt-6">
            @csrf
            @method('DELETE')

            <input type="hidden" name="id" :value="deleteId">

            <div class="flex justify-end gap-3">
                <button type="button" @click="showDelete = false"
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

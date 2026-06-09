<!-- Deactivate Modal -->
<div x-show="showDeactivateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showDeactivateModal = false"></div>

    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 relative z-[110]">
        <h3 class="text-2xl font-semibold text-amber-600 mb-4">Deactivate Account</h3>
        <p class="text-gray-600 text-lg">
            Are you sure you want to deactivate <strong x-text="userToDeactivate.name"></strong>?
        </p>

        <div class="mt-10 flex justify-end gap-4">
            <button @click="showDeactivateModal = false"
                    class="px-8 py-3 text-gray-700 hover:bg-gray-100 rounded-2xl font-medium">Cancel</button>
            <form :action="deactivateUrl" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="px-8 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-2xl font-semibold">
                    Yes, Deactivate
                </button>
            </form>
        </div>
    </div>
</div>

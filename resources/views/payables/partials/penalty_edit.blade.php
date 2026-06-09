<!-- Penalty Edit Modal -->
<div
    x-show="$store.penaltyModal.penaltyEditOpen"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;"
    @keydown.escape.window="$store.penaltyModal.closeEdit()">

    <div class="bg-white rounded-xl max-w-md w-full mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold">Edit Penalty</h3>
        </div>

        <form
            :action="`/penalties/${$store.penaltyModal.editingPenalty.id}`"
            method="POST"
            class="p-6 space-y-4">

            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Penalty Name</label>
                <input
                    type="text"
                    name="name"
                    :value="$store.penaltyModal.editingPenalty.name"
                    required
                    class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" required
                        class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="tuition"
                            :selected="$store.penaltyModal.editingPenalty.type === 'tuition'">Tuition</option>
                    <option value="enrollment"
                            :selected="$store.penaltyModal.editingPenalty.type === 'enrollment'">Enrollment</option>
                    <option value="electricity"
                            :selected="$store.penaltyModal.editingPenalty.type === 'electricity'">Electricity</option>
                    <option value="assessment"
                            :selected="$store.penaltyModal.editingPenalty.type === 'assessment'">Assessment</option>
                    <option value="uniforms"
                            :selected="$store.penaltyModal.editingPenalty.type === 'uniforms'">Uniforms</option>
                    <option value="graduation"
                            :selected="$store.penaltyModal.editingPenalty.type === 'graduation'">Graduation</option>
                    <option value="others"
                            :selected="$store.penaltyModal.editingPenalty.type === 'others'">Others</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₱)</label>
                <input
                    type="number"
                    name="amount"
                    step="0.01"
                    :value="$store.penaltyModal.editingPenalty.amount"
                    required
                    class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button
                    type="button"
                    @click="$store.penaltyModal.closeEdit()"
                    class="px-5 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                    Cancel
                </button>
                <button
                    type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Penalty
                </button>
            </div>
        </form>
    </div>
</div>

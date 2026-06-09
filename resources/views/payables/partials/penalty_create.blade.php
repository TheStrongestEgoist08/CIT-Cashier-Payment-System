<!-- Penalty Create Modal -->
<div x-show="penaltyCreateOpen"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
     style="display: none;">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold">Create New Penalty</h3>
        </div>

        <form action="{{ route('penalties.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Penalty Name</label>
                <input type="text" name="name" required
                       class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" required
                        class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select Type</option>
                    <option value="tuition">Tuition</option>
                    <option value="enrollment">Enrollment</option>
                    <option value="electricity">Electricity</option>
                    <option value="assessment">Assessment</option>
                    <option value="uniforms">Uniforms</option>
                    <option value="graduation">Graduation</option>
                    <option value="others">Others</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₱)</label>
                <input type="number" name="amount" step="0.01" required
                       class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" @click="penaltyCreateOpen = false"
                        class="px-5 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Create Penalty
                </button>
            </div>
        </form>
    </div>
</div>

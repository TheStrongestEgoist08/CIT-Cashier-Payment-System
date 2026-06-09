{{-- Others Details --}}
<div class="space-y-8">

    <!-- Due Date & Amount -->
    <div>
        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
            OTHER PAYABLE DETAILS
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Due Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                <input
                    type="date"
                    x-model="form.details.due_date"
                    name="details[due_date]"
                    required
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 py-3">
            </div>

            <!-- Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                    <input
                        type="number"
                        x-model="form.details.amount"
                        name="details[amount]"
                        required
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg font-medium"
                        placeholder="0.00"
                        step="0.01">
                </div>
            </div>

        </div>
    </div>

</div>

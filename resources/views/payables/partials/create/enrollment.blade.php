
{{-- Enrollment Details --}}
<div class="space-y-8">

    <!-- Amount Section -->
    <div>
        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
            ENROLLMENT FEE
        </h4>

        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <span class="font-medium text-gray-800">Amount</span>
                <div class="relative w-64">
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

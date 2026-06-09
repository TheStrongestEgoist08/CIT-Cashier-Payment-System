
{{-- Uniform Details --}}
<div class="space-y-8">

    <!-- Sex Selection -->
    <div>
        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
            UNIFORM TYPE
        </h4>

        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Sex / Category</label>
            <select
                x-model="form.details.sex"
                name="details[sex]"
                required
                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 py-3">
                <option value="">Select Category</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Unisex">Unisex</option>
            </select>
        </div>
    </div>

    <!-- Sizes Section -->
    <div>
        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
            SIZE PRICES
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Size S -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-800">Small (S)</span>
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number"
                               x-model="form.details.sizes[0].amount"
                               name="details[sizes][0][amount]"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg"
                               placeholder="0.00"
                               step="0.01">
                    </div>
                </div>
                <input type="hidden" name="details[sizes][0][size]" value="S">
            </div>

            <!-- Size M -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-800">Medium (M)</span>
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number"
                               x-model="form.details.sizes[1].amount"
                               name="details[sizes][1][amount]"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg"
                               placeholder="0.00"
                               step="0.01">
                    </div>
                </div>
                <input type="hidden" name="details[sizes][1][size]" value="M">
            </div>

            <!-- Size L -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-800">Large (L)</span>
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number"
                               x-model="form.details.sizes[2].amount"
                               name="details[sizes][2][amount]"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg"
                               placeholder="0.00"
                               step="0.01">
                    </div>
                </div>
                <input type="hidden" name="details[sizes][2][size]" value="L">
            </div>

            <!-- Size XL -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-800">Extra Large (XL)</span>
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number"
                               x-model="form.details.sizes[3].amount"
                               name="details[sizes][3][amount]"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg"
                               placeholder="0.00"
                               step="0.01">
                    </div>
                </div>
                <input type="hidden" name="details[sizes][3][size]" value="XL">
            </div>

            <!-- Size XXL -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-800">XX Large (XXL)</span>
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number"
                               x-model="form.details.sizes[4].amount"
                               name="details[sizes][4][amount]"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg"
                               placeholder="0.00"
                               step="0.01">
                    </div>
                </div>
                <input type="hidden" name="details[sizes][4][size]" value="XXL">
            </div>

            <!-- Size XXXL -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-800">XXX Large (XXXL)</span>
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number"
                               x-model="form.details.sizes[5].amount"
                               name="details[sizes][5][amount]"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg"
                               placeholder="0.00"
                               step="0.01">
                    </div>
                </div>
                <input type="hidden" name="details[sizes][5][size]" value="XXXL">
            </div>

        </div>
    </div>

</div>

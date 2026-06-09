
{{-- Tuition Details --}}
<div class="space-y-8">

    <!-- Period Section -->
    <div>
        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
            BILLING PERIOD
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Month</label>
                <select x-model="form.details.start_month" name="details[start_month]" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 py-3">
                    <option value="">Select Month</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Month</label>
                <select x-model="form.details.end_month" name="details[end_month]" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 py-3">
                    <option value="">Select Month</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Due Day of Month</label>
                <input type="number" x-model="form.details.due_day" name="details[due_day]" min="1" max="31"
                       class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 py-3" placeholder="e.g. 15">
            </div>
        </div>
    </div>

    <!-- Student Types -->
    <div>
        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
            STUDENT CLASSIFICATION FEES
        </h4>

        <div class="space-y-4">

            <!-- Regular -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-800">Regular Payee</span>
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number"
                               x-model="form.details.student_types[0].amount"
                               name="details[student_types][0][amount]"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg"
                               placeholder="0.00">
                    </div>
                </div>
                <input type="hidden" name="details[student_types][0][classification]" value="Regular Payee">
            </div>

            <!-- ESC -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-800">ESC Grantee</span>
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number"
                               x-model="form.details.student_types[1].amount"
                               name="details[student_types][1][amount]"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg"
                               placeholder="0.00">
                    </div>
                </div>
                <input type="hidden" name="details[student_types][1][classification]" value="ESC Grantee">
            </div>

            <!-- Voucher -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-800">Voucher Beneficiary</span>
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number"
                               x-model="form.details.student_types[2].amount"
                               name="details[student_types][2][amount]"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg"
                               placeholder="0.00">
                    </div>
                </div>
                <input type="hidden" name="details[student_types][2][classification]" value="Voucher Beneficiary">
            </div>

        </div>
    </div>

</div>

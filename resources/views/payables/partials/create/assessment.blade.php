
{{-- Assessment Details --}}
<div class="space-y-8">

    <!-- Applicable To (Strands) -->
    <div>
        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
            APPLICABLE TO (STRANDS)
        </h4>

        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Strands</label>

            <select
                x-model="form.details.applicable_to"
                name="details[applicable_to]"
                multiple
                required
                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 py-3 min-h-[120px]">
                <option value="INDUSTRIAL TECHNOLOGIES">INDUSTRIAL TECHNOLOGIES</option>
                <option value="AUTOMOTIVE AND SMALL ENGINE TECHNOLOGIES">AUTOMOTIVE AND SMALL ENGINE TECHNOLOGIES</option>
                <option value="All Strands">All Strands</option>
            </select>

            <p class="text-xs text-gray-500 mt-2">Hold <strong>Ctrl</strong> (or Cmd on Mac) to select multiple strands.</p>
        </div>
    </div>

    <!-- Particulars / Fees -->
    <div>
        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
            ASSESSMENT PARTICULARS
        </h4>

        <div class="space-y-4">

            <!-- Assessment Fee -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-800">Assessment Fee</span>
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number"
                               x-model="form.details.particulars[0].assessment_fee"
                               name="details[particulars][0][assessment_fee]"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg"
                               placeholder="0.00"
                               step="0.01">
                    </div>
                </div>
            </div>

            <!-- Transportation Fee -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-800">Transportation Fee</span>
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number"
                               x-model="form.details.particulars[1].transportation_fee"
                               name="details[particulars][1][transportation_fee]"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg"
                               placeholder="0.00"
                               step="0.01">
                    </div>
                </div>
            </div>

            <!-- Certificate Fee -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-800">Certificate Fee</span>
                    <div class="relative w-52">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number"
                               x-model="form.details.particulars[2].certificate_fee"
                               name="details[particulars][2][certificate_fee]"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 text-lg"
                               placeholder="0.00"
                               step="0.01">
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

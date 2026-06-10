{{-- Student Payables Modal --}}
<div
    x-cloak
    x-show="viewModal"
    x-transition.opacity.duration.400
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-md"
    @keydown.escape.window="viewModal = false; resetSelection()"
>
    <form
        method="POST"
        action="{{ route('students.store') }}"
        class="relative w-full max-w-7xl mx-4 bg-white rounded-3xl shadow-2xl flex flex-col max-h-[92vh] overflow-hidden"
        @submit.prevent="handleSubmit"
    >
        @csrf
        <input type="hidden" name="student_id" :value="selectedStudent ? selectedStudent.id : ''">

        <!-- Hidden fields for selected items -->
        <template x-for="(item, index) in selectedPayables" :key="index">
            <div>
                <input type="hidden" :name="`selected_payables[${index}][student_payable_id]`" :value="item.id || ''">
                <input type="hidden" :name="`selected_payables[${index}][payable_id]`" :value="item.payable_id">
                <input type="hidden" :name="`selected_payables[${index}][payable_name]`" :value="item.payable_name">
                <input type="hidden" :name="`selected_payables[${index}][payable_type]`" :value="item.payable_type">
                <input type="hidden" :name="`selected_payables[${index}][charge_amount]`" :value="item.charge_amount">
                <input type="hidden" :name="`selected_payables[${index}][penalty_amount]`" :value="item.penalty_amount || 0">
                <input type="hidden" :name="`selected_payables[${index}][school_year]`" :value="item.school_year">
                <input type="hidden" :name="`selected_payables[${index}][quantity]`" :value="item.quantity || 1">
                <input type="hidden" :name="`selected_payables[${index}][size]`" :value="item.size || ''">
                <input type="hidden" :name="`selected_payables[${index}][OR]`" :value="item.OR || ''">
                <input type="hidden" :name="`selected_payables[${index}][is_exempted]`" :value="item.is_exempted ? 1 : 0">
            </div>
        </template>

        <!-- HEADER -->
        <header class="bg-gradient-to-r from-indigo-700 via-blue-700 to-blue-600 px-8 py-6 flex justify-between items-center rounded-t-3xl">
            <div>
                <h2 class="text-2xl font-semibold text-white tracking-tight">Create Payment</h2>
                <p
                    class="text-blue-100 text-sm mt-1"
                    x-text="selectedStudent ? `${selectedStudent.student_id || selectedStudent.id} - ${selectedStudent.complete_name}${selectedStudent.sex ? ` (${selectedStudent.sex})` : ''}` : ''"
                ></p>
            </div>
            <button @click="viewModal = false; resetSelection()" type="button"
                    class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white/20 hover:bg-white/30 text-white text-2xl leading-none">×</button>
        </header>

        <!-- BODY -->
        <div class="flex-1 flex gap-6 p-6 md:p-8 overflow-hidden">

            <!-- LEFT: Payables -->
            <div class="flex-1 overflow-y-auto pr-4 custom-scroll space-y-10">

                <!-- NON-REPEATABLE SECTION -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        📋 Standard Payables
                    </h2>

                    <template x-for="(group, groupKey) in groupedNonRepeatables()" :key="groupKey">
                        <section class="border border-gray-200 rounded-3xl p-6 bg-white mb-8">
                            <h3 class="text-lg font-semibold mb-6" x-text="group.label"></h3>

                            <template x-for="(items, typeName) in group.types" :key="typeName">
                                <div class="mb-8 last:mb-0">
                                    <h4 class="text-xl font-semibold mb-5 flex items-center gap-3 border-b pb-2">
                                        <span class="w-4 h-4 rounded-full" :class="getTypeColor(typeName)"></span>
                                        <span x-text="typeName"></span>
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                        <template x-for="item in items" :key="item.id">
                                            <div
                                                @click="addPayable(item)"
                                                :class="{
                                                    'ring-2 ring-green-500 bg-green-50 border-green-300': isSelected(item),
                                                    'hover:border-green-400 hover:shadow-lg': isSelectable(item),
                                                    'opacity-75 cursor-not-allowed': !isSelectable(item),
                                                    'relative': true
                                                }"
                                                class="bg-white border border-gray-200 rounded-3xl p-5 cursor-pointer transition-all overflow-hidden">

                                                <!-- PAID Ribbon -->
                                                <template x-if="!item.is_repeatable && (item.status === 'paid')">
                                                    <div class="absolute -right-8 top-6 rotate-45 bg-emerald-600 text-white text-xs font-bold px-10 py-1 shadow-md z-10"
                                                         style="transform: rotate(45deg);">
                                                        PAID
                                                    </div>
                                                </template>

                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <p class="font-semibold text-gray-800" x-text="item.payable_name"></p>
                                                    </div>
                                                    <div x-show="isSelected(item)"
                                                         class="w-8 h-8 bg-green-500 text-white rounded-2xl flex items-center justify-center text-xl flex-shrink-0">✓</div>
                                                </div>

                                                <div class="mt-auto pt-6 space-y-3">
                                                    <div>
                                                        <p class="text-xs text-gray-500">Balance</p>
                                                        <p class="text-2xl font-bold text-emerald-600"
                                                           x-text="'₱' + Number(item.amount || (Number(item.charge_amount) + Number(item.penalty_amount || 0))).toLocaleString('en-PH', {minimumFractionDigits: 2})"></p>
                                                    </div>

                                                    <template x-if="Number(item.penalty_amount) > 0">
                                                        <div class="flex items-center gap-1.5 text-red-600">
                                                            <span class="text-base">⚠</span>
                                                            <div>
                                                                <p class="text-xs">Penalty</p>
                                                                <p class="font-semibold"
                                                                   x-text="'₱' + Number(item.penalty_amount).toLocaleString('en-PH', {minimumFractionDigits: 2})"></p>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </section>
                    </template>
                </div>

                <!-- REPEATABLE SECTION -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        Other Items
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        <template x-for="item in repeatables" :key="item.payable_id">
                            <div
                                @click="addPayable(item)"
                                :class="{
                                    'ring-2 ring-green-500 bg-green-50 border-green-300': isSelected(item),
                                    'hover:border-green-400 hover:shadow-lg': true,
                                    'relative': true
                                }"
                                class="bg-white border border-gray-200 rounded-3xl p-5 cursor-pointer transition-all">

                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800" x-text="item.payable_name"></p>
                                    </div>
                                    <div x-show="isSelected(item)"
                                         class="w-8 h-8 bg-green-500 text-white rounded-2xl flex items-center justify-center text-xl flex-shrink-0">✓</div>
                                </div>

                                <div class="mt-auto pt-6">
                                    <p class="text-xs text-gray-500">Price</p>
                                    <p class="text-2xl font-bold text-emerald-600"
                                       x-text="'₱' + getBasePrice(item).toLocaleString('en-PH', {minimumFractionDigits: 2})"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <!-- RIGHT: Summary -->
            <div class="w-full md:w-96 bg-gray-50 border border-gray-100 rounded-3xl p-6 flex flex-col overflow-hidden">
                <h3 class="text-lg font-semibold text-gray-700 mb-5">Payment Breakdown</h3>

                <div class="flex-1 overflow-y-auto space-y-4 custom-scroll">
                    <template x-if="selectedPayables.length === 0">
                        <div class="h-full flex items-center justify-center text-gray-400 italic py-12">
                            No items selected yet
                        </div>
                    </template>

                    <template x-for="(item, index) in selectedPayables" :key="index">
                        <div class="bg-white border border-gray-200 rounded-2xl p-5 space-y-4">
                            <div class="flex justify-between">
                                <div>
                                    <p class="font-medium" x-text="item.payable_name"></p>
                                    <p class="text-xs text-gray-500" x-text="item.school_year"></p>
                                </div>
                                <button type="button" @click="removePayable(index)"
                                        class="text-red-500 hover:text-red-600 text-sm">Remove</button>
                            </div>

                            <!-- Repeatable Config -->
                            <template x-if="item.is_repeatable">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs text-gray-500 block mb-1">Quantity</label>
                                        <input type="number" min="1" max="10"
                                               @input="updateQuantity(index, $event)"
                                               :value="item.quantity"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-center">
                                    </div>

                                    <div x-show="isUniform(item)">
                                        <label class="text-xs text-gray-500 block mb-1">Size</label>
                                        <select @change="updateSize(index, $event)"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="">Select Size</option>
                                            <template x-for="sizeOption in getSizeOptions(item)" :key="sizeOption.size">
                                                <option :value="sizeOption.size"
                                                        :selected="item.size === sizeOption.size">
                                                    <span x-text="sizeOption.size"></span> - ₱<span x-text="sizeOption.amount"></span>
                                                </option>
                                            </template>
                                        </select>
                                    </div>

                                    <div x-show="isUniform(item)" class="mt-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox"
                                                @change="toggleExempted(index, $event)"
                                                :checked="item.is_exempted || false"
                                                class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                            <span class="text-sm font-medium text-gray-700">Mark as Exempted (Bigay)</span>
                                        </label>
                                    </div>
                                </div>
                            </template>

                            <!-- Breakdown -->
                            <div class="space-y-2 pt-2 border-t border-gray-100 text-sm">
                                <template x-if="item.amount !== 0">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Base Amount</span>
                                        <span class="font-medium"
                                            x-text="'₱ ' + Number(item.amount).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                                    </div>
                                </template>

                                <template x-if="Number(item.penalty_amount) > 0">
                                    <div class="flex justify-between text-red-600">
                                        <span>Penalty</span>
                                        <span class="font-medium"
                                              x-text="'₱ ' + Number(item.penalty_amount).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                                    </div>
                                </template>

                                <div class="border-t border-gray-200 pt-3 flex justify-between font-semibold text-base">
                                    <span class="text-gray-800">Item Total</span>
                                    <span class="text-emerald-600"
                                          x-text="'₱ ' + Number(item.total_amount || (Number(item.charge_amount) + Number(item.penalty_amount || 0))).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Grand Total -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex justify-between items-end text-2xl font-bold text-gray-800">
                        <span>Grand Total</span>
                        <span class="text-emerald-600"
                              x-text="'₱ ' + totalWithPenalty().toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                    </div>
                    <p class="text-xs text-gray-500 text-right mt-1">Inclusive of penalties</p>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="border-t p-6 bg-white rounded-b-3xl flex flex-col md:flex-row gap-4 items-end">
            <!-- OR Number -->
            <div class="flex-1 w-full md:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-2">Original Receipt (OR) Number</label>
                <input
                    type="text"
                    max="50"
                    x-model="orNumber"
                    name="or_number"
                    @input="applyORToAll()"
                    placeholder="Enter OR Number (optional)"
                    class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg font-medium">
                <p class="text-xs text-gray-500 mt-1">This OR will be applied to all selected items</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button type="button" @click="viewModal = false; resetSelection()"
                        class="px-8 py-3 rounded-2xl font-medium text-gray-600 hover:bg-gray-100 whitespace-nowrap">
                    Cancel
                </button>
                <button type="submit" :disabled="isSubmitting || selectedPayables.length === 0"
                        class="px-10 py-3 bg-blue-600 text-white rounded-2xl font-medium hover:bg-blue-700 disabled:opacity-70 whitespace-nowrap">
                    <span x-show="!isSubmitting">Confirm Payment</span>
                    <span x-show="isSubmitting">Processing...</span>
                </button>
            </div>
        </div>
    </form>
</div>

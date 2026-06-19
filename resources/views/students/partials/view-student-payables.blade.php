
{{-- View Only Student Payables Modal --}}
<div
    x-cloak
    x-show="viewOnlyModal"
    x-transition.opacity.duration.400
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-md"
    @keydown.escape.window="viewOnlyModal = false"
>
    <div class="relative w-full max-w-7xl mx-4 bg-white rounded-3xl shadow-2xl flex flex-col max-h-[92vh] overflow-hidden">

        <!-- HEADER -->
        <header class="bg-gradient-to-r from-indigo-700 via-blue-700 to-blue-600 px-8 py-6 flex justify-between items-center rounded-t-3xl flex-shrink-0">
            <div>
                <h2 class="text-2xl font-semibold text-white tracking-tight">Student Account Details</h2>
                <p
                    class="text-blue-100 text-sm mt-1"
                    x-text="selectedStudent ? `${selectedStudent.student_id || selectedStudent.id} - ${selectedStudent.complete_name}${selectedStudent.sex ? ` (${selectedStudent.sex})` : ''}` : ''"
                ></p>
            </div>
            <button @click="viewOnlyModal = false" type="button"
                    class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white/20 hover:bg-white/30 text-white text-2xl leading-none">×</button>
        </header>

        <!-- BODY - Scrollable Area -->
        <div class="flex-1 overflow-hidden flex flex-col">
            <div class="flex-1 overflow-y-auto p-6 md:p-8 custom-scroll space-y-10">

                <!-- NON-REPEATABLE SECTION ONLY -->
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
                                            <div class="bg-white border border-gray-200 rounded-3xl p-5 relative transition-all overflow-hidden">

                                                <!-- Status Ribbon -->
                                                <template x-if="item.status === 'paid'">
                                                    <div class="absolute -right-8 top-6 rotate-45 bg-emerald-600 text-white text-xs font-bold px-10 py-1 shadow-md z-10" style="transform: rotate(45deg);">
                                                        PAID
                                                    </div>
                                                </template>

                                                <template x-if="item.status === 'exempted'">
                                                    <div class="absolute -right-8 top-6 rotate-45 bg-amber-600 text-white text-xs font-bold px-10 py-1 shadow-md z-10" style="transform: rotate(45deg);">
                                                        Bigay/Labas
                                                    </div>
                                                </template>

                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <p class="font-semibold text-gray-800" x-text="item.payable_name"></p>
                                                        <p class="text-sm text-gray-500 mt-1" x-text="item.school_year"></p>
                                                    </div>
                                                </div>

                                                <div class="mt-6 space-y-3">
                                                    <div>
                                                        <p class="text-xs text-gray-500">Amount</p>
                                                        <p class="text-2xl font-bold text-emerald-600"
                                                           x-text="'₱' + Number(item.amount || 0).toLocaleString('en-PH', {minimumFractionDigits: 2})"></p>
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

                                                    <div class="pt-3 border-t border-gray-100">
                                                        <p class="text-xs text-gray-500">Balance</p>
                                                        <p class="text-xl font-bold"
                                                           :class="payableBalance(item) > 0 ? 'text-red-600' : 'text-emerald-600'"
                                                           x-text="'₱' + payableBalance(item).toLocaleString('en-PH', {minimumFractionDigits: 2})"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </section>
                    </template>
                </div>

            </div>
        </div>

        <!-- FOOTER -->
        <div class="border-t px-6 py-6 bg-white rounded-b-3xl flex justify-end flex-shrink-0">
            <button @click="viewOnlyModal = false"
                    class="px-8 py-3 bg-gray-700 text-white rounded-2xl font-medium hover:bg-gray-800">
                Close
            </button>
        </div>
    </div>
</div>

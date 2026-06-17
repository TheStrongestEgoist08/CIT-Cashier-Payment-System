<!-- Receipt Modal -->
<div
    x-cloak
    x-show="viewModal"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
    @keydown.escape.window="viewModal = false"
>
    <div
        class="bg-white w-full max-w-4xl h-[90vh] rounded-3xl shadow-2xl overflow-hidden flex flex-col"
    >

        <!-- Fixed Header -->
        <div class="flex-shrink-0 bg-gradient-to-r from-indigo-700 to-blue-700 text-white px-8 py-6">

            <div class="flex justify-between items-start">

                <div>

                    <h2 class="text-2xl font-bold">
                        Payment Receipt
                    </h2>

                    <p
                        class="text-indigo-100 text-sm mt-1"
                        x-text="'Transaction #: ' + (selectedTransaction?.transaction_code || '')"
                    ></p>

                </div>

                <button
                    @click="viewModal = false"
                    class="text-3xl leading-none hover:text-gray-200"
                >
                    ×
                </button>

            </div>

        </div>

        <!-- Scrollable Body -->
        <div class="flex-1 overflow-y-auto bg-slate-50">

            <!-- Receipt Paper -->
            <div class="max-w-3xl mx-auto bg-white my-6 rounded-2xl shadow-lg border">

                <!-- Receipt Title -->
                <div class="text-center p-8">

                    <div
                        class="w-16 h-16 mx-auto bg-emerald-100 rounded-full flex items-center justify-center mb-4"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-8 h-8 text-emerald-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m5 0a9 9 0 11-18 0 9 9 0 0118 0z"
                            />

                        </svg>
                    </div>

                    <h3 class="text-3xl font-bold text-slate-800">
                        OFFICIAL RECEIPT
                    </h3>

                    <p class="text-slate-500 mt-2">
                        Payment Confirmation
                    </p>

                </div>

                <div class="border-t border-dashed"></div>

                <!-- Student Information -->
                <div class="p-8">

                    <h4 class="font-bold text-slate-800 mb-5">
                        Student Information
                    </h4>

                    <div class="grid md:grid-cols-2 gap-6">

                        <div>

                            <p class="text-xs uppercase text-slate-500 tracking-widest">
                                Student Name
                            </p>

                            <p
                                class="font-semibold text-slate-800 mt-1"
                                x-text="selectedTransaction?.student?.complete_name"
                            ></p>

                        </div>

                        <div>

                            <p class="text-xs uppercase text-slate-500 tracking-widest">
                                Student ID
                            </p>

                            <p
                                class="font-semibold text-slate-800 mt-1"
                                x-text="selectedTransaction?.student?.student_id"
                            ></p>

                        </div>

                    </div>

                </div>

                <div class="border-t border-dashed"></div>

                <!-- Summary Cards -->
                <div class="p-8">

                    <div class="grid md:grid-cols-2 gap-4">

                        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5">

                            <p class="text-xs uppercase text-emerald-700">
                                Total Paid
                            </p>

                            <p
                                class="text-3xl font-bold text-emerald-600 mt-2"
                                x-text="'₱' + Number(selectedTransaction?.total_amount || 0).toLocaleString('en-PH',{minimumFractionDigits:2})"
                            ></p>

                        </div>

                        <div class="bg-red-50 border border-red-200 rounded-2xl p-5">

                            <p class="text-xs uppercase text-red-700">
                                Total Penalty
                            </p>

                            <p
                                class="text-3xl font-bold text-red-600 mt-2"
                                x-text="'₱' + Number(selectedTransaction?.total_penalty || 0).toLocaleString('en-PH',{minimumFractionDigits:2})"
                            ></p>

                        </div>

                    </div>

                </div>

                <div class="border-t border-dashed"></div>

                <!-- Payment Breakdown -->
                <div class="p-8">

                    <h4 class="font-bold text-slate-800 mb-5">
                        Payment Breakdown
                    </h4>

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            <thead>

                                <tr class="bg-slate-100 text-xs uppercase tracking-wider text-slate-600">

                                    <th class="px-4 py-3 text-left">
                                        Payable
                                    </th>

                                    <th class="px-4 py-3 text-center">
                                        Qty
                                    </th>

                                    <th class="px-4 py-3 text-right">
                                        Amount
                                    </th>

                                    <th class="px-4 py-3 text-right">
                                        Penalty
                                    </th>

                                    <th class="px-4 py-3 text-right">
                                        Total
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <template
                                    x-for="item in (selectedTransaction?.payables || [])"
                                    :key="item.student_payable_id"
                                >

                                    <tr class="border-b border-dashed">

                                        <td class="px-4 py-4">

                                            <div
                                                class="font-medium text-slate-800"
                                                x-text="item.payable_name"
                                            ></div>

                                            <div
                                                class="text-xs text-slate-500 capitalize"
                                                x-text="item.payable_type + ' • ' + item.school_year"
                                            ></div>

                                        </td>

                                        <td
                                            class="px-4 py-4 text-center"
                                            x-text="item.quantity"
                                        ></td>

                                        <td class="px-4 py-4 text-right">

                                            ₱<span
                                                x-text="Number(item.amount || 0).toLocaleString('en-PH',{minimumFractionDigits:2})"
                                            ></span>

                                        </td>

                                        <td class="px-4 py-4 text-right text-red-600">

                                            ₱<span
                                                x-text="Number(item.penalty_amount || 0).toLocaleString('en-PH',{minimumFractionDigits:2})"
                                            ></span>

                                        </td>

                                        <td class="px-4 py-4 text-right font-semibold">

                                            ₱<span
                                                x-text="Number(item.total || 0).toLocaleString('en-PH',{minimumFractionDigits:2})"
                                            ></span>

                                        </td>

                                    </tr>

                                </template>

                            </tbody>

                        </table>

                    </div>

                </div>

                <!-- Remarks -->
                <template x-if="selectedTransaction?.remarks">

                    <div class="p-8 border-t border-dashed">

                        <h4 class="font-bold text-slate-800 mb-3">
                            Remarks
                        </h4>

                        <div
                            class="bg-slate-50 border rounded-xl p-4 text-sm italic text-slate-600"
                            x-text="selectedTransaction?.remarks"
                        ></div>

                    </div>

                </template>

                <!-- Receipt Footer -->
                <div class="border-t border-dashed p-8 text-center">

                    <p class="font-semibold text-slate-800">
                        Thank you for your payment.
                    </p>

                </div>

            </div>

        </div>

        <!-- Fixed Footer -->
        <div class="flex-shrink-0 bg-white border-t p-5 flex justify-end gap-3">
            <button
                type="button"
                @click="
                    const iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    iframe.src = `{{ url('/transactions/print') }}/${selectedTransaction.id}`;

                    iframe.onload = () => {
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                    };

                    document.body.appendChild(iframe);
                "
                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition"
            >Print Receipt</button>


            <button
                @click="viewModal = false"
                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium transition"
            >Close</button>

        </div>

    </div>
</div>

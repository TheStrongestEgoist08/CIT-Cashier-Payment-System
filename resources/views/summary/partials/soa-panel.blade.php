
{{-- Export Section --}}
<div class="bg-white shadow-sm rounded-2xl p-6 mb-6 border border-gray-100">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Export & Print Documents</h2>
        <span class="text-sm text-gray-500">All filtered students will be included</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Statement of Account --}}
        <form
            id="soaForm"
            method="POST"
            target="_blank"
            class="bg-gray-50 rounded-2xl p-6 border border-gray-200 hover:border-blue-200 transition-all flex flex-col h-full"
        >
            @csrf

            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl">
                    📋
                </div>
                <div>
                    <h3 class="font-semibold text-lg text-gray-800">Statement of Account</h3>
                    <p class="text-sm text-gray-500">Detailed monthly breakdown</p>
                </div>
            </div>

            <div class="flex-1 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Month</label>
                    <input
                        type="month"
                        name="month"
                        id="soa_month"
                        class="w-full border border-gray-300 rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                </div>
            </div>

            <!-- Hidden Students -->
            <div id="soaStudentsContainer" class="hidden">
                @foreach ($students as $student)
                    <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                @endforeach
            </div>

            <div class="flex gap-3 mt-auto pt-6">
                <button
                    type="button"
                    onclick="submitSOAForm('print')"
                    class="flex-1 flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3.5 px-6 rounded-2xl transition-all active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                        <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1"/>
                        <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                    </svg>
                    Print SOA
                </button>

                <button
                    type="button"
                    onclick="submitSOAForm('export')"
                    class="flex-1 flex justify-center items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium py-3.5 px-6 rounded-2xl transition-all active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-pdf-fill" viewBox="0 0 16 16">
                        <path d="M5.523 10.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 4.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
                        <path fill-rule="evenodd" d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m.165 11.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.6 11.6 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
                    </svg>
                    Export PDF
                </button>
            </div>
        </form>

        {{-- Summary of Account --}}
        <form
            id="summaryOfAccountsForm"
            method="POST"
            target="_blank"
            class="bg-gray-50 rounded-2xl p-6 border border-gray-200 hover:border-emerald-200 transition-all flex flex-col h-full"
        >
            @csrf

            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl">
                    📊
                </div>
                <div>
                    <h3 class="font-semibold text-lg text-gray-800">Summary of Account</h3>
                    <p class="text-sm text-gray-500">Overall account summary</p>
                </div>
            </div>

            <!-- Centered Message -->
            <div class="flex-1 flex items-center justify-center py-8">
                <div class="text-center">
                    <p class="text-gray-600 font-medium">Generate overall account summary</p>
                    <p class="text-sm text-gray-500 mt-1">for all filtered students</p>
                </div>
            </div>

            <!-- Hidden Students -->
            <div id="summaryStudentsContainer" class="hidden">
                @foreach ($students as $student)
                    <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                @endforeach
            </div>

            <div class="flex gap-3 mt-auto pt-6">
                <button
                    type="button"
                    onclick="submitSummaryForm('print')"
                    class="flex-1 flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3.5 px-6 rounded-2xl transition-all active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                        <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1"/>
                        <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                    </svg>
                    Print Summary
                </button>

                <button
                    type="button"
                    onclick="submitSummaryForm('export')"
                    class="flex-1 flex justify-center items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium py-3.5 px-6 rounded-2xl transition-all active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-pdf-fill" viewBox="0 0 16 16">
                        <path d="M5.523 10.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 4.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
                        <path fill-rule="evenodd" d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m.165 11.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.6 11.6 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
                    </svg>
                    Export PDF
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function submitSOAForm(type) {
        const form = document.getElementById('soaForm');
        const month = document.getElementById('soa_month').value;

        if (!month) {
            alert('Please select a month for the Statement of Account.');
            return;
        }

        if (type === 'print') {
            form.action = "{{ route('summary.printStudentSOA') }}";
        } else {
            form.action = "{{ route('summary.exportStudentSOA') }}";
        }
        form.submit();
    }

    function submitSummaryForm(type) {
        const form = document.getElementById('summaryOfAccountsForm');

        if (type === 'print') {
            form.action = "{{ route('summary.printStudentSummaryOfAccount') }}";
        } else {
            form.action = "{{ route('summary.exportStudentSummaryOfAccount') }}";
        }
        form.submit();
    }

    // Save month preference
    document.addEventListener('DOMContentLoaded', function () {
        const monthInput = document.getElementById('soa_month');
        const savedMonth = localStorage.getItem('soa_month');
        if (savedMonth) monthInput.value = savedMonth;

        monthInput.addEventListener('change', () => {
            localStorage.setItem('soa_month', monthInput.value);
        });
    });
</script>

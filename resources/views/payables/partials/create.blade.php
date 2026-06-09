
{{-- Create Payable Modal --}}
<div
    x-data="{
        form: {
            name: '',
            type: '',
            school_year: '',
            is_repeatable: false,
            details: {}
        },

        initDetails() {
            if (this.form.type === 'tuition') {
                this.form.details = {
                    start_month: '',
                    end_month: '',
                    due_day: null,
                    student_types: [
                        { classification: 'Regular Payee', amount: null },
                        { classification: 'ESC Grantee', amount: null },
                        { classification: 'Voucher Beneficiary', amount: null }
                    ]
                };
            }
            else if (this.form.type === 'enrollment') {
                this.form.details = {
                    amount: null
                };
            }
            else if (this.form.type === 'electricity') {
                this.form.details = {
                    start_month: '',
                    end_month: '',
                    due_day: null,
                    amount: null
                };
            }
            else if (this.form.type === 'assessment') {
                this.form.details = {
                    applicable_to: [],
                    particulars: [
                        { assessment_fee: null },
                        { transportation_fee: null },
                        { certificate_fee: null },
                    ]
                };
            }
            else if (this.form.type === 'uniforms') {
                this.form.details = {
                    sex: null,
                    sizes: [
                        { size: 'S', amount: null },
                        { size: 'M', amount: null },
                        { size: 'L', amount: null },
                        { size: 'XL', amount: null },
                        { size: 'XXL', amount: null },
                        { size: 'XXXL', amount: null },
                    ]
                };
            }
            else if (this.form.type === 'others') {
                this.form.details = {
                    due_date: null,
                    amount: null
                };
            }
            else if (this.form.type === 'graduation') {
                this.form.details = {
                    due_date: null,
                    year_level: 'Grade 12',
                    amount: null
                };
            }
            else {
                this.form.details = {};
            }
        },
        preventBodyScroll() {
            if (this.createModalOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
    }"
    x-init="initDetails()"
    x-effect="preventBodyScroll()"
    x-cloak
>

    <div x-show="createModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
        <form
            @click.away="createModalOpen = false"
            class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden max-h-[95vh] flex flex-col"
            method="POST"
            action="{{ route('payables.store') }}"
            @submit.prevent="
                if(confirm('Are you sure you want to create this payable?')) {
                    $el.submit();
                }
            "
        >
            @csrf

            <!-- Header -->
            <div class="px-6 py-5 border-b flex items-center justify-between bg-gray-50 rounded-t-3xl">
                <h2 class="text-xl font-semibold text-gray-800">Create Payable</h2>
                <button type="button" @click="createModalOpen = false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" x-model="form.name" name="name" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 py-3">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select x-model="form.type" @change="initDetails()" name="type" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 py-3">
                                <option value="" disabled>Select type</option>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">School Year</label>
                            <input type="text" x-model="form.school_year" name="school_year" placeholder="2025-2026" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 py-3">
                        </div>
                    </div>

                    <!-- Is Repeatable Checkbox -->
                    <div class="mb-6">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input
                                    type="checkbox"
                                    x-model="form.is_repeatable"
                                    name="is_repeatable"
                                    value="1"
                                    class="peer w-6 h-6 accent-blue-600 border-2 border-gray-300 rounded-xl
                                        focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                                        transition-all duration-200 cursor-pointer
                                        checked:border-blue-600"
                                >
                            </div>
                            <div>
                                <span class="text-base font-medium text-gray-700 group-hover:text-gray-900 transition-colors">
                                    Repeatable Payable
                                </span>
                                <p class="text-sm text-gray-500">can be purchaced again</p>
                            </div>
                        </label>
                    </div>

                    <!-- Details -->
                    <template class="mt-8" x-if="form.type === 'tuition'">
                        @include('payables.partials.create.tuition')
                    </template>

                    <template class="mt-8" x-if="form.type === 'enrollment'">
                        @include('payables.partials.create.enrollment')
                    </template>

                    <template class="mt-8" x-if="form.type === 'electricity'">
                        @include('payables.partials.create.electricity')
                    </template>

                    <template class="mt-8" x-if="form.type === 'uniforms'">
                        @include('payables.partials.create.uniform')
                    </template>

                    <template class="mt-8" x-if="form.type === 'assessment'">
                        @include('payables.partials.create.assessment')
                    </template>

                    <template class="mt-8" x-if="form.type === 'graduation'">
                        @include('payables.partials.create.graduation')
                    </template>

                    <template class="mt-8" x-if="form.type === 'others'">
                        @include('payables.partials.create.others')
                    </template>

                    <input type="hidden" name="details" :value="JSON.stringify(form.details || {})">
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t p-6 bg-gray-50 flex justify-end gap-3 rounded-b-3xl">
                <button type="button" @click="createModalOpen = false" class="px-6 py-3 rounded-2xl border hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-8 py-3 rounded-2xl bg-blue-600 text-white hover:bg-blue-700">Save Payable</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Payable Modal --}}
<div
    x-data="{
        createModalOpen: false,  // just in case
        editModalOpen: false,

        form: {
            id: null,
            name: '',
            type: '',
            school_year: '',
            is_repeatable: false,
            details: {}
        },

        initEdit(payable) {
            this.form.id = payable.id;
            this.form.name = payable.name;
            this.form.type = payable.type;
            this.form.school_year = payable.school_year;
            this.form.is_repeatable = !!payable.is_repeatable;
            this.form.details = payable.details || {};
            this.editModalOpen = true;
        },

        preventBodyScroll() {
            if (this.editModalOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
    }"
    x-cloak
    x-effect="preventBodyScroll()"
    @open-edit.window="initEdit($event.detail)"
>

    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
        <form
            @click.away="editModalOpen = false"
            class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden max-h-[95vh] flex flex-col"
            method="POST"
            :action="`/payables/${form.id}`"
            @submit.prevent="
                if(confirm('Are you sure you want to update this payable?')) {
                    $el.submit();
                }
            "
        >
            @csrf
            @method('PUT')

            <!-- Header -->
            <div class="px-6 py-5 border-b flex items-center justify-between bg-gray-50 rounded-t-3xl">
                <h2 class="text-xl font-semibold text-gray-800">Edit Payable</h2>
                <button type="button" @click="editModalOpen = false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" x-model="form.name" name="name" required
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 py-3">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <input
                                type="text"
                                :value="form.type ? form.type.charAt(0).toUpperCase() + form.type.slice(1) : ''"
                                disabled
                                class="w-full rounded-xl border-gray-300 bg-gray-100 text-gray-500 py-3 cursor-not-allowed">
                            <input type="hidden" name="type" :value="form.type">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">School Year</label>
                            <input type="text" x-model="form.school_year" name="school_year" required
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 py-3">
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative">
                                    <input
                                        type="checkbox"
                                        x-model="form.is_repeatable"
                                        name="is_repeatable"
                                        value="1"
                                        
                                        :checked="form.is_repeatable"
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
                    </div>

                    <!-- Details using edit partials -->
                    <template x-if="form.type === 'tuition'">
                        @include('payables.partials.edit.tuition')
                    </template>
                    <template x-if="form.type === 'enrollment'">
                        @include('payables.partials.edit.enrollment')
                    </template>
                    <template x-if="form.type === 'electricity'">
                        @include('payables.partials.edit.electricity')
                    </template>
                    <template x-if="form.type === 'uniforms'">
                        @include('payables.partials.edit.uniform')
                    </template>
                    <template x-if="form.type === 'assessment'">
                        @include('payables.partials.edit.assessment')
                    </template>
                    <template x-if="form.type === 'graduation'">
                        @include('payables.partials.edit.graduation')
                    </template>
                    <template x-if="form.type === 'others'">
                        @include('payables.partials.edit.others')
                    </template>

                    <input type="hidden" name="details" :value="JSON.stringify(form.details || {})">
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t p-6 bg-gray-50 flex justify-end gap-3 rounded-b-3xl">
                <button type="button" @click="editModalOpen = false" class="px-6 py-3 rounded-2xl border hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-8 py-3 rounded-2xl bg-blue-600 text-white hover:bg-blue-700">Update Payable</button>
            </div>
        </form>
    </div>
</div>

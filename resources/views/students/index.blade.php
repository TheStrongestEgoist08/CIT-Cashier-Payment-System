{{-- Student Accounts --}}
<x-app-layout>
    <div
        x-data="{
            paymentModal: false,
            viewOnlyModal: false,
            selectedStudent: null,
            nonRepeatables: [],
            repeatables: [],
            selectedPayables: [],
            isSubmitting: false,
            orNumber: '',

            async openPaymentModal(studentId) {
                try {
                    const response = await fetch(`/students/${studentId}/payables`);
                    const data = await response.json();

                    this.selectedStudent = data.student;
                    this.nonRepeatables = data.non_repeatables || [];
                    this.repeatables = data.repeatables || [];
                    this.selectedPayables = [];
                    this.orNumber = '';
                    this.paymentModal = true;
                } catch (error) {
                    console.error(error);
                    alert('Failed to load payables. Please check your internet or backend route.');
                }
            },

            async openViewOnlyModal(studentId) {
                try {
                    const response = await fetch(`/students/${studentId}/payables`);
                    const data = await response.json();

                    this.selectedStudent = data.student;
                    this.nonRepeatables = data.non_repeatables || [];
                    this.repeatables = data.repeatables || [];
                    this.viewOnlyModal = true;
                } catch (error) {
                    console.error(error);
                    alert('Failed to load student account details.');
                }
            },

            payableBalance(item) {
                return Number(item.amount || 0) - Number(item.paid_amount || 0) + Number(item.penalty_amount) || 0;
            },

            isSelectable(item) {
                if (item.is_repeatable) return true;
                return this.payableBalance(item) > 0 && item.status !== 'paid';
            },

            getBasePrice(item) {
                if (!item.is_repeatable || !item.details) return 0;

                if (item.payable_type?.toLowerCase() === 'uniforms' && item.details.sizes) {
                    return Number(item.details.sizes[0]?.amount || 0);
                }
                return Number(item.details.amount || 0);
            },

            isUniform(item) {
                return item.is_repeatable &&
                    item.payable_type?.toLowerCase() === 'uniforms' &&
                    item.details?.sizes;
            },

            getSizeOptions(item) {
                if (!this.isUniform(item)) return [];
                return item.details.sizes || [];
            },

            addPayable(item) {
                if (!this.isSelectable(item)) return;

                const exists = this.selectedPayables.findIndex(p =>
                    (p.id && p.id === item.id) || (!p.id && p.payable_id === item.payable_id)
                );

                if (exists === -1) {
                    const newItem = { ...item };

                    newItem.is_exempted = false;

                    if (newItem.is_repeatable) {
                        newItem.quantity = 1;
                        newItem.size = '';
                        newItem.charge_amount = this.getBasePrice(newItem);
                    } else {
                        newItem.charge_amount = this.payableBalance(newItem);
                    }

                    this.selectedPayables.push(newItem);
                } else {
                    this.selectedPayables.splice(exists, 1);
                }
            },

            updateQuantity(index, event) {
                const qty = Math.min(Math.max(1, parseInt(event.target.value) || 1), 10);
                this.selectedPayables[index].quantity = qty;
                this.recalculateChargeAmount(index);
            },

            updateSize(index, event) {
                const size = event.target.value;
                this.selectedPayables[index].size = size;
                this.recalculateChargeAmount(index);
            },

            recalculateChargeAmount(index) {
                const item = this.selectedPayables[index];
                if (!item.is_repeatable) return;

                let price = this.getBasePrice(item);

                if (this.isUniform(item) && item.size) {
                    const selectedSize = item.details.sizes.find(s => s.size === item.size);
                    if (selectedSize) {
                        price = Number(selectedSize.amount);
                    }
                }

                item.charge_amount = price * (item.quantity || 1);
            },

            toggleExempted(index, event) {
                this.selectedPayables[index].is_exempted = event.target.checked;
            },

            removePayable(index) {
                this.selectedPayables.splice(index, 1);
            },

            isSelected(item) {
                return this.selectedPayables.some(p =>
                    (p.id && p.id === item.id) || (!p.id && p.payable_id === item.payable_id)
                );
            },

            total() {
                return this.selectedPayables.reduce((sum, p) => sum + Number(p.charge_amount || 0), 0);
            },

            getTypeColor(typeName) {
                const colors = {
                    'Tuition': 'bg-blue-500',
                    'Electricity': 'bg-orange-500',
                    'Uniforms': 'bg-violet-500',
                    'Enrollment': 'bg-emerald-500',
                    'Assessment': 'bg-amber-500',
                    'Graduation': 'bg-rose-500',
                    'Others': 'bg-gray-500'
                };
                return colors[typeName] || 'bg-gray-500';
            },

            parseMonthYear(name) {
                const months = {
                    'january': 1, 'february': 2, 'march': 3, 'april': 4, 'may': 5, 'june': 6,
                    'july': 7, 'august': 8, 'september': 9, 'october': 10, 'november': 11, 'december': 12
                };

                const match = name.toLowerCase().match(/(\w+)\s+(\d{4})/);
                if (match) {
                    const monthName = match[1];
                    const year = parseInt(match[2]);
                    return { month: months[monthName] || 99, year };
                }
                return { month: 99, year: 9999 };
            },

            groupedNonRepeatables() {
                const payables = this.nonRepeatables || [];
                const groups = {};

                payables.forEach(item => {
                    const sy = item.school_year || 'Unknown SY';
                    const grade = item.grade_level || 'Unknown Grade';
                    const groupKey = `${sy} • ${grade}`;

                    if (!groups[groupKey]) {
                        groups[groupKey] = { label: groupKey, types: {} };
                    }

                    let type = (item.payable_type || 'others').toLowerCase().trim();

                    const typeMap = {
                        'tuition': 'Tuition',
                        'enrollment': 'Enrollment',
                        'electricity': 'Electricity',
                        'assessment': 'Assessment',
                        'uniforms': 'Uniforms',
                        'graduation': 'Graduation',
                        'others': 'Others'
                    };

                    const typeKey = typeMap[type] || 'Others';

                    if (!groups[groupKey].types[typeKey]) {
                        groups[groupKey].types[typeKey] = [];
                    }
                    groups[groupKey].types[typeKey].push(item);
                });

                // Sorting
                Object.keys(groups).forEach(groupKey => {
                    const types = groups[groupKey].types;
                    if (types['Tuition']) {
                        types['Tuition'].sort((a, b) => {
                            const dateA = this.parseMonthYear(a.payable_name);
                            const dateB = this.parseMonthYear(b.payable_name);
                            if (dateA.year !== dateB.year) return dateA.year - dateB.year;
                            return dateA.month - dateB.month;
                        });
                    }
                    if (types['Electricity']) {
                        types['Electricity'].sort((a, b) => {
                            const dateA = this.parseMonthYear(a.payable_name);
                            const dateB = this.parseMonthYear(b.payable_name);
                            if (dateA.year !== dateB.year) return dateA.year - dateB.year;
                            return dateA.month - dateB.month;
                        });
                    }
                });

                return groups;
            },

            resetSelection() {
                this.selectedPayables = [];
                this.orNumber = '';
            },

            async handleSubmit() {
                if (this.selectedPayables.length === 0) {
                    alert('Please select at least one payable.');
                    return;
                }
                if (this.isSubmitting) return;
                if (!confirm('Proceed with this payment?')) return;

                this.isSubmitting = true;

                const form = this.$el.closest('form');
                if (!form) {
                    alert('Form not found.');
                    this.isSubmitting = false;
                    return;
                }

                const fd = new FormData(form);

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: fd
                    });

                    let data;
                    const contentType = res.headers.get('content-type');

                    if (contentType && contentType.includes('application/json')) {
                        data = await res.json();
                    } else {
                        data = { message: await res.text() || 'Server error' };
                    }

                    if (res.ok && data.success) {
                        this.paymentModal = false;

                        if (data.print_url) {
                            const iframe = document.createElement('iframe');
                            iframe.style.display = 'none';
                            iframe.src = data.print_url;

                            iframe.onload = function () {
                                iframe.contentWindow.focus();
                                iframe.contentWindow.print();
                            };

                            document.body.appendChild(iframe);
                        }

                        alert('Payment recorded successfully.');
                    } else {
                        alert(data.message || `Payment failed (${res.status})`);
                    }
                } catch (err) {
                    console.error(err);
                    alert('Submission failed. Please check browser console (F12).');
                } finally {
                    this.isSubmitting = false;
                }
            },

            totalWithPenalty() {
                return this.selectedPayables.reduce((sum, item) => {
                    return sum + Number(item.total_amount || (Number(item.charge_amount) + Number(item.penalty_amount || 0)));
                }, 0);
            },

            applyORToAll() {
                this.selectedPayables.forEach(item => {
                    item.OR = this.orNumber;
                });
            },
        }"
    >
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Accounts') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- Filter & Import Bar -->
                <div class="bg-white shadow-sm rounded-xl mb-6">
                    <div class="p-4">
                        <form method="GET" action="{{ route('students') }}" id="filterForm">
                            <div class="flex items-center gap-3 overflow-x-auto">

                                <input type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Search..."
                                    class="min-w-[250px] flex-1 border border-gray-300 rounded-lg px-4 py-2.5">

                                <select name="school_year" class="min-w-[160px] border border-gray-300 rounded-lg px-4 py-2.5">
                                    <option value="">School Year</option>
                                    @foreach($schoolYears as $sy)
                                        <option value="{{ $sy }}" {{ request('school_year') == $sy ? 'selected' : '' }}>{{ $sy }}</option>
                                    @endforeach
                                </select>

                                <select name="grade_level" class="min-w-[140px] border border-gray-300 rounded-lg px-4 py-2.5">
                                    <option value="">Grade</option>
                                    <option value="Grade 11" {{ request('grade_level') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                    <option value="Grade 12" {{ request('grade_level') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                </select>

                                <select name="section" class="min-w-[160px] border border-gray-300 rounded-lg px-4 py-2.5">
                                    <option value="">Section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section }}" {{ request('section') == $section ? 'selected' : '' }}>{{ $section }}</option>
                                    @endforeach
                                </select>

                                <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg whitespace-nowrap">
                                    Filter
                                </button>

                                <button type="button" onclick="clearFilters()"
                                        class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg whitespace-nowrap">
                                    Clear
                                </button>

                                @if (Auth::user()->role === 'admin')
                                    <button type="button" onclick="document.getElementById('importModal').showModal()"
                                            class="bg-green-600 text-white px-5 py-2.5 rounded-lg whitespace-nowrap">
                                        Import Students
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @if (session('success'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                {{ session('error') }}
                            </div>
                        @endif

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">S.Y</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sex</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Section</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classification</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($students as $student)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->school_year }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->student_id }}</td>
                                        <td class="px-6 py-4">{{ $student->complete_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->sex }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->grade_level }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->section }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                {{ $student->classification === 'ESC Grantee' ? 'bg-blue-100 text-blue-800' :
                                                ($student->classification === 'Voucher Beneficiary' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ $student->classification }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if(Auth::user()->role === 'admin')
                                                <button
                                                    @click="openViewOnlyModal({{ $student->id }})"
                                                    class="text-blue-600 hover:text-blue-800 transition-colors p-1"
                                                    title="View Account"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5 16.477 5 20.268 7.943 21.542 12 20.268 16.057 16.477 19 12 19 7.523 19 3.732 16.057 2.458 12z" />
                                                    </svg>
                                                </button>
                                            @else
                                                <button
                                                    @click="openPaymentModal({{ $student->id }})"
                                                    class="text-blue-600 hover:text-blue-800 transition-colors p-1"
                                                    title="View Payables"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5 16.477 5 20.268 7.943 21.542 12 20.268 16.057 16.477 19 12 19 7.523 19 3.732 16.057 2.458 12z" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                            No students found.
                                        </td>
                                    </tr>
                                @endempty
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $students->links() }}
                        </div>
                    </div>
                </div>
            </div>

            @include('students.partials.import')
            @include('students.partials.payment-modal')
            @include('students.partials.view-student-payables')
        </div>
    </div>

    <script>
        function clearFilters() {
            document.getElementById('filterForm').reset();
            window.location.href = "{{ route('students') }}";
        }
    </script>
</x-app-layout>

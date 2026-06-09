{{-- Transactions --}}
<x-app-layout>
    <div
        x-data="{
            viewModal: false,
            selectedTransaction: null,

            async openTransactionModal(id) {
                try {
                    const res = await fetch(`/transactions/${id}`);
                    const data = await res.json();

                    if (data.transaction) {
                        this.selectedTransaction = data.transaction;
                        this.viewModal = true;
                    }
                } catch (e) {
                    console.error(e);
                    alert('Failed to load transaction details.');
                }
            }
        }"
    >
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Transactions') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- Search Bar -->
                <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <form method="GET" class="flex gap-4 items-center">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search by Student ID or Student Name..."
                                class="flex-1 border border-gray-300 rounded-xl px-5 py-3 focus:outline-none focus:border-blue-500"
                            >
                            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl hover:bg-blue-700">
                                Search
                            </button>
                            @if(request('search'))
                                <a href="{{ route('transactions') }}"
                                   class="text-gray-600 hover:text-gray-800 px-5 py-3">
                                    Clear
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($transactions as $t)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-mono font-medium">{{ $t->transaction_code }}</td>
                                        <td class="px-6 py-4">{{ $t->student->student_id ?? '—' }}</td>
                                        <td class="px-6 py-4">{{ $t->student->complete_name ?? '—' }}</td>
                                        <td class="px-6 py-4 font-semibold text-emerald-600">
                                            ₱{{ number_format($t->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">
                                            {{ $t->created_at->format('M d, Y • h:i A') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <button @click="openTransactionModal({{ $t->id }})"
                                                    class="text-blue-600 hover:text-blue-800 font-medium">
                                                View Details →
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-12 text-gray-500">
                                            No transactions found.
                                        </td>
                                    </tr>
                                @endempty
                            </tbody>
                        </table>

                        <div class="mt-6">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include Modal Partial -->
        @include('transactions.partials.view')
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Total Transactions</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $summary['total_transactions'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Total Amount</div>
                    <div class="text-3xl font-bold text-green-600">₱{{ number_format($summary['total_amount'], 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Total Penalty</div>
                    <div class="text-3xl font-bold text-red-600">₱{{ number_format($summary['total_penalty'], 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Grand Total</div>
                    <div class="text-3xl font-bold text-indigo-600">₱{{ number_format($summary['grand_total'], 2) }}</div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                    <div class="flex flex-wrap gap-2">
                        <a href="?period=today"
                           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('period') === 'today' ? 'bg-indigo-600 text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                            Today
                        </a>
                        <a href="?period=this_week"
                           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('period') === 'this_week' ? 'bg-indigo-600 text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                            This Week
                        </a>
                        <a href="?period=this_month"
                           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('period') === 'this_month' || !request('period') ? 'bg-indigo-600 text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                            This Month
                        </a>
                        <a href="?period=this_year"
                           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('period') === 'this_year' ? 'bg-indigo-600 text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                            This Year
                        </a>
                    </div>

                    <div class="flex gap-3">
                        <form method="GET" class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search transaction or student..."
                                   class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500 w-80">
                            <button type="submit"
                                    class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700">
                                Search
                            </button>
                        </form>

                        <a href="{{ route('reports.export.excel') . '?' . request()->getQueryString() }}"
                           class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2">
                            📊 Excel
                        </a>
                    </div>
                </div>
            </div>

            {{-- Transactions Table --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-28">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                                        {{ $transaction->transaction_code }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium">{{ $transaction->student->complete_name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400">{{ $transaction->student->student_id ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button onclick="togglePayables({{ $transaction->id }})"
                                                class="inline-flex items-center px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-sm font-medium transition">
                                            View Details
                                        </button>
                                    </td>
                                </tr>

                                {{-- Clean Details Section --}}
                                <tr id="payables-{{ $transaction->id }}" class="hidden bg-gray-50">
                                    <td colspan="4" class="px-6 py-6">
                                        <div class="max-w-3xl mx-auto bg-white border border-gray-200 rounded-2xl p-6">

                                            <div class="flex justify-between items-start mb-6 pb-4 border-b">
                                                <div>
                                                    <div class="font-semibold">Transaction Details</div>
                                                    <div class="text-sm text-gray-500">#{{ $transaction->transaction_code }}</div>
                                                </div>
                                                <div class="text-sm text-right">
                                                    {{ $transaction->created_at->format('M d, Y') }}<br>
                                                    <span class="text-gray-500">{{ $transaction->created_at->format('h:i A') }}</span>
                                                </div>
                                            </div>

                                            <div class="mb-6 pb-6 border-b">
                                                <div class="text-xs uppercase text-gray-500 mb-1">Student</div>
                                                <div class="font-medium">{{ $transaction->student->complete_name ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $transaction->student->student_id ?? '' }}</div>
                                            </div>

                                            <div class="mb-6">
                                                <div class="text-xs uppercase tracking-widest text-gray-500 mb-3">PAYABLE ITEMS</div>
                                                @if($transaction->payables && count($transaction->payables) > 0)
                                                    <div class="space-y-4">
                                                        @foreach($transaction->payables as $item)
                                                            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                                                                <div class="flex justify-between">
                                                                    <div>
                                                                        <div class="font-medium">{{ $item['payable_name'] ?? 'Item' }}</div>
                                                                        <div class="text-sm text-gray-500">
                                                                            {{ $item['school_year'] ?? '' }} • {{ ucfirst($item['payable_type'] ?? '') }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <div class="font-medium">₱{{ number_format($item['amount'] ?? 0, 2) }}</div>
                                                                        @if(!empty($item['penalty_amount']) && $item['penalty_amount'] > 0)
                                                                            <div class="text-xs text-red-600">
                                                                                Penalty: ₱{{ number_format($item['penalty_amount'], 2) }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="flex justify-between text-sm mt-2 pt-2 border-t border-gray-200">
                                                                    <span class="text-gray-500">Quantity: {{ $item['quantity'] ?? 1 }}</span>
                                                                    <span class="font-medium">
                                                                        Total: ₱{{ number_format(($item['amount'] ?? 0) + ($item['penalty_amount'] ?? 0), 2) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-gray-500 italic py-4">No payable items found.</p>
                                                @endif
                                            </div>

                                            <div class="pt-4 border-t">
                                                <div class="flex justify-between text-base">
                                                    <span class="text-gray-600">Total Amount</span>
                                                    <span class="font-medium">₱{{ number_format($transaction->total_amount, 2) }}</span>
                                                </div>
                                                @if($transaction->total_penalty > 0)
                                                <div class="flex justify-between text-base text-red-600">
                                                    <span>Total Penalty</span>
                                                    <span>₱{{ number_format($transaction->total_penalty, 2) }}</span>
                                                </div>
                                                @endif
                                                <div class="flex justify-between text-xl font-bold mt-3 pt-3 border-t">
                                                    <span>GRAND TOTAL</span>
                                                    <span>₱{{ number_format($transaction->total_amount + $transaction->total_penalty, 2) }}</span>
                                                </div>
                                            </div>

                                            @if($transaction->remarks)
                                                <div class="mt-6 pt-5 border-t">
                                                    <div class="text-xs uppercase text-gray-500 mb-1">Remarks</div>
                                                    <p class="text-gray-700">{{ $transaction->remarks }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        No transactions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePayables(id) {
            document.getElementById(`payables-${id}`).classList.toggle('hidden');
        }
    </script>
</x-app-layout>

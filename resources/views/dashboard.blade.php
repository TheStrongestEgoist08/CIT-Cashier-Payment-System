<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 tracking-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">

                <!-- Income This Month -->
                <div class="bg-white rounded-3xl shadow p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Income This Month</p>
                            <h3 class="text-3xl font-bold text-emerald-600 mt-3">
                                ₱{{ number_format($incomeThisMonth, 2) }}
                            </h3>
                            <div class="flex items-center gap-1 mt-2 text-emerald-600 text-sm font-medium">
                                <span>↑ 12%</span>
                                <span class="text-slate-400">from last month</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl">
                            💰
                        </div>
                    </div>
                </div>

                <!-- Income This Year -->
                <div class="bg-white rounded-3xl shadow p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Income This Year</p>
                            <h3 class="text-3xl font-bold text-blue-600 mt-3">
                                ₱{{ number_format($incomeThisYear, 2) }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-3xl">
                            📈
                        </div>
                    </div>
                </div>

                <!-- Transactions This Month -->
                <div class="bg-white rounded-3xl shadow p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Transactions This Month</p>
                            <h3 class="text-3xl font-bold text-indigo-600 mt-3">
                                {{ number_format($transactionsThisMonth) }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-3xl">
                            🔄
                        </div>
                    </div>
                </div>

                <!-- Total Penalties Collected -->
                <div class="bg-white rounded-3xl shadow p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Penalties Collected</p>
                            <h3 class="text-3xl font-bold text-rose-600 mt-3">
                                ₱{{ number_format($totalPenalties, 2) }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 bg-rose-100 rounded-2xl flex items-center justify-center text-3xl">
                            ⚠️
                        </div>
                    </div>
                </div>

            </div>

            {{-- Chart Section --}}
            <div class="bg-white rounded-3xl shadow p-8 mb-10">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Revenue Overview</h3>
                    <span class="text-sm text-slate-500">Last 6 Months</span>
                </div>
                {!! $chart->container() !!}
            </div>

            {{-- Recent Transactions --}}
            <div class="bg-white rounded-3xl shadow overflow-hidden">

                <div class="px-8 py-6 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-slate-800">Recent Transactions</h3>
                            <p class="text-sm text-slate-500 mt-1">Latest payment activities</p>
                        </div>
                        <div class="text-4xl">🧾</div>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">

                    @forelse($recentTransactions as $transaction)

                        <div class="px-8 py-6 hover:bg-slate-50 transition-all duration-200 flex items-center justify-between">

                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 bg-emerald-100 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                                    💰
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-800">
                                        {{ $transaction->student->complete_name ?? 'Unknown Student' }}
                                    </h4>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-xs font-mono px-3 py-1 bg-indigo-100 text-indigo-700 rounded-xl">
                                            {{ $transaction->transaction_code }}
                                        </span>
                                        <span class="text-sm text-slate-500">
                                            {{ $transaction->created_at->format('M d, Y • h:i A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="text-2xl font-bold text-emerald-600">
                                    ₱{{ number_format($transaction->total_amount, 2) }}
                                </div>
                                @if($transaction->total_penalty > 0)
                                    <div class="text-xs text-rose-500 mt-1">
                                        Penalty: ₱{{ number_format($transaction->total_penalty, 2) }}
                                    </div>
                                @else
                                    <div class="text-xs text-emerald-500 mt-1">No Penalty</div>
                                @endif
                            </div>

                        </div>

                    @empty

                        <div class="py-20 text-center">
                            <div class="text-6xl mb-4 text-slate-200">🧾</div>
                            <h4 class="font-semibold text-slate-600">No transactions yet</h4>
                            <p class="text-slate-500 mt-2">Recent payments will appear here.</p>
                        </div>

                    @endforelse

                </div>
            </div>

        </div>
    </div>

    <!-- Chart Scripts -->
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}

</x-app-layout>

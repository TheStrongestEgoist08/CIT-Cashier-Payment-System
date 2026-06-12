<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Payables</h3>

            <div class="flex flex-col sm:flex-row gap-3 items-center">
                <form method="GET" action="{{ route('payables') }}" class="flex gap-2">
                    {{-- Search by name --}}
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search payable..."
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    {{-- Filter by type --}}
                    <select
                        name="type"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">All Types</option>
                        <option value="tuition" {{ request('type') == 'tuition' ? 'selected' : '' }}>Tuition</option>
                        <option value="enrollment" {{ request('type') == 'enrollment' ? 'selected' : '' }}>Enrollment</option>
                        <option value="assessment" {{ request('type') == 'assessment' ? 'selected' : '' }}>Assessment</option>
                        <option value="uniforms" {{ request('type') == 'uniforms' ? 'selected' : '' }}>Uniforms</option>
                        <option value="electricity" {{ request('type') == 'electricity' ? 'selected' : '' }}>Electricity</option>
                        <option value="graduation" {{ request('type') == 'graduation' ? 'selected' : '' }}>Graduation</option>
                        <option value="others" {{ request('type') == 'others' ? 'selected' : '' }}>Others</option>
                    </select>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        Filter
                    </button>

                    <a
                        href="{{ route('payables') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
                    >
                        Clear
                    </a>
                </form>

                <button
                    @click="createModalOpen = true"
                    class="px-5 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition"
                >
                    + Add Payable
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($payables as $payable)
                @php
                    $typeColors = [
                        'graduation'  => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                        'others'      => 'bg-slate-100 text-slate-700 border-slate-200',
                        'assessment'  => 'bg-amber-100 text-amber-700 border-amber-200',
                        'uniforms'    => 'bg-cyan-100 text-cyan-700 border-cyan-200',
                        'electricity' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        'enrollment'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'tuition'     => 'bg-blue-100 text-blue-700 border-blue-200',
                    ];

                    $typeIcons = [
                        'graduation'  => '🎓',
                        'others'      => '📁',
                        'assessment'  => '📝',
                        'uniforms'    => '👕',
                        'electricity' => '⚡',
                        'enrollment'  => '📋',
                        'tuition'     => '🏫',
                    ];

                    $bannerClass = $typeColors[strtolower($payable->type)] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                    $icon = $typeIcons[strtolower($payable->type)] ?? '💰';
                @endphp

                <div class="rounded-xl border border-gray-200 overflow-hidden bg-white shadow-sm hover:shadow-lg transition">

                    <div class="px-5 py-3 border-b {{ $bannerClass }}">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold flex items-center gap-2">
                                <span class="text-lg">{{ $icon }}</span>
                                {{ ucfirst($payable->type) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">
                            {{ $payable->name }}
                        </h3>

                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-500">School Year</span>
                                <span>{{ $payable->school_year }}</span>
                            </div>
                            @if($payable->is_repeatable)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-500">Repeatable</span>
                                    <span class="text-green-600">Yes</span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex justify-end gap-2">
                            <button
                                @click="$dispatch('open-edit', @js($payable->toArray()))"
                                class="px-4 py-1.5 text-sm rounded-md bg-yellow-500 text-white hover:bg-yellow-600">
                                Edit
                            </button>

                            <form action="{{ route('payables.destroy', $payable) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ addslashes($payable->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-1.5 text-sm rounded-md bg-red-500 text-white hover:bg-red-600">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-5xl mb-3">📄</div>
                    <p class="text-gray-500">No payables found.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

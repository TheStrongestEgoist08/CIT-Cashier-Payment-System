<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Penalties</h3>
            <button @click="penaltyCreateOpen = true"
                    class="px-5 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                + Add Penalty
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($penalties as $penalty)
                        <tr>
                            <td class="px-6 py-4">{{ $penalty->name }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100">
                                    {{ ucfirst($penalty->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium">
                                ₱{{ number_format($penalty->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button @click="$dispatch('open-penalty-edit', @js($penalty->toArray()))"
                                        class="text-yellow-600 hover:text-yellow-700">Edit</button>
                                <form action="{{ route('penalties.destroy', $penalty) }}" method="POST"
                                      class="inline" onsubmit="return confirm('Delete this penalty?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                No penalties found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- Activity --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activity Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filters --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form id="activityForm" method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">

                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                            <select name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                            <select name="action" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">All Actions</option>
                                <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                                <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                                <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                <option value="viewed" {{ request('action') == 'viewed' ? 'selected' : '' }}>Viewed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="flex items-end gap-3 md:col-span-2">
                            <button type="button" onclick="submitActivityForm('filter')"
                                    class="px-6 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm font-medium flex-1 md:flex-none">
                                Filter
                            </button>

                            <a href="{{ route('activities') }}"
                               class="px-6 py-2.5 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition text-sm font-medium flex-1 md:flex-none text-center">
                                Clear
                            </a>

                            <button type="button" onclick="submitActivityForm('print')"
                                    class="flex-1 md:flex-none px-6 py-2.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition text-sm font-medium">
                                Print
                            </button>

                            <button type="button" onclick="submitActivityForm('export')"
                                    class="flex-1 md:flex-none px-6 py-2.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-sm font-medium">
                                Export
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $log->user?->name ?? 'System' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                            {{ match($log->action) {
                                                'created' => 'bg-green-100 text-green-700',
                                                'updated' => 'bg-blue-100 text-blue-700',
                                                'deleted' => 'bg-red-100 text-red-700',
                                                'viewed'  => 'bg-gray-100 text-gray-700',
                                                default => 'bg-gray-100 text-gray-700'
                                            } }}">
                                            {{ ucfirst($log->action ?? '') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="max-w-md line-clamp-2">
                                            {{ $log->description }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $log->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                        {{ $log->ip_address }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <button onclick="viewLog({{ $log->toJson() }})"
                                                class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        No activity logs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>

            @include('activities.partials.view-modal')
        </div>
    </div>
</x-app-layout>

<script>
    function submitActivityForm(type) {
        const form = document.getElementById('activityForm');

        if (type === 'print') {
            form.action = "{{ route('activities.print.pdf') }}";
            form.method = "POST";
            form.target = "_blank";
        } else if (type === 'export') {
            form.action = "{{ route('activities.export.pdf') }}";
            form.method = "POST";
            form.target = "_blank";
        } else {
            form.action = "{{ route('activities') }}";
            form.method = "GET";
            form.target = "_self";
        }

        form.submit();
    }
</script>

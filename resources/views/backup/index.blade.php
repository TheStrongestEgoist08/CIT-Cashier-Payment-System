{{-- Backup & Restore --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Backup & Restore') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
                    {!! session('success') !!}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Backup Path Selection -->
                    <div class="mb-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Select Backup Path
                        </label>

                        <div class="flex gap-3">
                            <select id="backup_path_id"
                                    name="backup_path_id"
                                    class="flex-1 border-gray-300 rounded-lg px-4 py-3 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Choose a backup path --</option>
                                @foreach($paths as $path)
                                    <option value="{{ $path->id }}"
                                            data-path="{{ $path->path }}"
                                            {{ $path->is_default ? 'selected' : '' }}>
                                        {{ $path->name }}
                                        <span class="text-gray-500">({{ $path->path }})</span>
                                        {{ $path->is_default ? '(Default)' : '' }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="button" onclick="verifySelectedPath()"
                                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg whitespace-nowrap">
                                ✅ Verify & Load
                            </button>

                            <button type="button" onclick="showAddPathModal()"
                                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg whitespace-nowrap">
                                + Add New Path
                            </button>
                        </div>

                        <div id="path-status" class="mt-3 text-sm min-h-[24px]"></div>
                    </div>

                    <!-- Automatic Backup Schedule -->
                    <div class="mb-12 p-6 bg-white border border-gray-200 rounded-xl">
                        <h3 class="text-lg font-semibold mb-6">Automatic Backup Schedule</h3>

                        <form method="POST" action="{{ route('backup.schedule.update') }}" id="schedule-form">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <!-- Enable Toggle -->
                                <div class="md:col-span-2">
                                    <label class="flex items-center gap-3">
                                        <input type="checkbox" name="is_enabled" id="is_enabled"
                                               class="w-5 h-5 text-green-600 rounded focus:ring-green-500"
                                               {{ $schedule->is_enabled ? 'checked' : '' }}>
                                        <span class="text-gray-700 font-medium">Enable Automatic Backups</span>
                                    </label>
                                </div>

                                <!-- Frequency -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Frequency</label>
                                    <select name="frequency" id="frequency"
                                            class="w-full border-gray-300 rounded-lg px-4 py-3">
                                        <option value="daily" {{ $schedule->frequency == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ $schedule->frequency == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="biweekly" {{ $schedule->frequency == 'biweekly' ? 'selected' : '' }}>Bi-weekly (Every 2 weeks)</option>
                                        <option value="monthly" {{ $schedule->frequency == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ $schedule->frequency == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="yearly" {{ $schedule->frequency == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                </div>

                                <!-- Backup Time -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Backup Time</label>
                                    <input type="time" name="backup_time" id="backup_time"
                                           value="{{ $schedule->backup_time?->format('H:i') ?? '02:00' }}"
                                           class="w-full border-gray-300 rounded-lg px-4 py-3">
                                </div>

                                <!-- Day of Week -->
                                <div id="day_of_week_group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Day of Week</label>
                                    <select name="day_of_week" id="day_of_week" class="w-full border-gray-300 rounded-lg px-4 py-3">
                                        <option value="mon" {{ $schedule->day_of_week == 'mon' ? 'selected' : '' }}>Monday</option>
                                        <option value="tue" {{ $schedule->day_of_week == 'tue' ? 'selected' : '' }}>Tuesday</option>
                                        <option value="wed" {{ $schedule->day_of_week == 'wed' ? 'selected' : '' }}>Wednesday</option>
                                        <option value="thu" {{ $schedule->day_of_week == 'thu' ? 'selected' : '' }}>Thursday</option>
                                        <option value="fri" {{ $schedule->day_of_week == 'fri' ? 'selected' : '' }}>Friday</option>
                                        <option value="sat" {{ $schedule->day_of_week == 'sat' ? 'selected' : '' }}>Saturday</option>
                                        <option value="sun" {{ $schedule->day_of_week == 'sun' ? 'selected' : '' }}>Sunday</option>
                                    </select>
                                </div>

                                <!-- Day of Month -->
                                <div id="day_of_month_group">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Day of Month (1-28)</label>
                                    <input type="number" name="day_of_month" id="day_of_month"
                                           value="{{ $schedule->day_of_month ?? 1 }}"
                                           min="1" max="28"
                                           class="w-full border-gray-300 rounded-lg px-4 py-3">
                                </div>

                                <!-- Backup Path for Schedule -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Backup Path for Schedule</label>
                                    <select name="backup_path_id" id="schedule_backup_path_id"
                                            class="w-full border-gray-300 rounded-lg px-4 py-3">
                                        @foreach($paths as $path)
                                            <option value="{{ $path->id }}"
                                                    {{ $schedule->backup_path_id == $path->id ? 'selected' : '' }}>
                                                {{ $path->name }} ({{ $path->path }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="mt-8">
                                <button type="submit"
                                        class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg">
                                    💾 Save Automatic Backup Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Manage Backup Paths -->
                    <div class="mb-10">
                        <h3 class="text-lg font-semibold mb-4">Manage Backup Paths</h3>
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Name</th>
                                        <th class="px-6 py-3 text-left">Path</th>
                                        <th class="px-6 py-3 text-center">Default</th>
                                        <th class="px-6 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($paths as $path)
                                        <tr>
                                            <td class="px-6 py-4 font-medium">{{ $path->name }}</td>
                                            <td class="px-6 py-4 text-gray-500">{{ $path->path }}</td>
                                            <td class="px-6 py-4 text-center">
                                                @if($path->is_default)
                                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Default</span>
                                                @else
                                                    <span class="text-gray-400">No</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center space-x-4">
                                                @if(!$path->is_default)
                                                    <button onclick="setDefaultPath({{ $path->id }}, '{{ addslashes($path->name) }}')"
                                                            class="text-blue-600 hover:text-blue-700 font-medium">
                                                        Set as Default
                                                    </button>
                                                    <button onclick="deletePath({{ $path->id }}, '{{ addslashes($path->name) }}')"
                                                            class="text-red-600 hover:text-red-800 font-medium">
                                                        🗑 Delete
                                                    </button>
                                                @else
                                                    <span class="text-gray-400 text-sm">Protected</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Create Backup -->
                    <div class="mb-12">
                        <h3 class="text-lg font-semibold mb-4">Create New Backup</h3>
                        <form method="POST" action="{{ route('backup.backup') }}" id="backup-form">
                            @csrf
                            <input type="hidden" name="backup_path_id" id="form_backup_path_id">

                            <button type="submit" onclick="prepareFormPath()"
                                    class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg">
                                💾 Create Backup Now
                            </button>
                        </form>
                    </div>

                    <hr class="my-8 border-gray-200">

                    <!-- Available Backups -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Available SQL Backups</h3>
                        <div id="backups-container">
                            <p class="text-gray-500 italic" id="backups-placeholder">Please select and verify a backup path first.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Add New Path Modal -->
    <div id="addPathModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 w-full max-w-md mx-4">
            <h3 class="text-xl font-semibold mb-6">Add New Backup Path</h3>

            <form method="POST" action="{{ route('backup.path.store') }}" id="add-path-form">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Path Name</label>
                    <input type="text" name="name" required
                           class="w-full border-gray-300 rounded-lg px-4 py-3"
                           placeholder="e.g. Production Backups">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Server Path</label>
                    <input type="text" name="path" required
                           class="w-full border-gray-300 rounded-lg px-4 py-3"
                           placeholder="/home/user/backups or D:\backups">
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="hideAddPathModal()"
                            class="flex-1 py-3 border border-gray-300 rounded-lg font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700">
                        Save Path
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentVerifiedPath = '';
        let currentPathId = null;

        function showAddPathModal() {
            document.getElementById('addPathModal').classList.remove('hidden');
        }

        function hideAddPathModal() {
            document.getElementById('addPathModal').classList.add('hidden');
        }

        async function verifySelectedPath() {
            const select = document.getElementById('backup_path_id');
            const pathId = select.value;
            const statusDiv = document.getElementById('path-status');

            if (!pathId) {
                statusDiv.innerHTML = `<span class="text-red-600">❌ Please select a backup path.</span>`;
                return;
            }

            statusDiv.innerHTML = `<span class="text-blue-600">Verifying path...</span>`;

            try {
                const response = await fetch('/backup/verify-path', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ backup_path_id: pathId })
                });

                const data = await response.json();

                if (data.success) {
                    currentVerifiedPath = data.path;
                    currentPathId = pathId;
                    localStorage.setItem('selectedBackupPathId', pathId);

                    statusDiv.innerHTML = `<span class="text-green-600 font-medium">✅ Path verified successfully</span>`;

                    loadBackups(data.backups || []);
                } else {
                    statusDiv.innerHTML = `<span class="text-red-600">❌ ${data.message || 'Invalid path'}</span>`;
                    document.getElementById('backups-container').innerHTML =
                        `<p class="text-gray-500 italic">Verification failed.</p>`;
                }
            } catch (error) {
                console.error('Verify error:', error);
                statusDiv.innerHTML = `<span class="text-red-600">❌ Connection error. Check console.</span>`;
            }
        }

        function loadBackups(backups) {
            const container = document.getElementById('backups-container');

            if (!backups || backups.length === 0) {
                container.innerHTML = `<p class="text-gray-500 italic">No SQL backup files found in this folder.</p>`;
                return;
            }

            let html = `
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">Filename</th>
                            <th class="px-6 py-3 text-left">Size</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
            `;

            backups.forEach(backup => {
                html += `
                    <tr>
                        <td class="px-6 py-4">${backup.name}</td>
                        <td class="px-6 py-4">${backup.size}</td>
                        <td class="px-6 py-4">${backup.date}</td>
                        <td class="px-6 py-4 text-center space-x-6">
                            <a href="/backup/download/${encodeURIComponent(backup.name)}" class="text-blue-600 hover:underline">Download</a>
                            <button onclick="restoreBackup('${addslashes(backup.name)}')" class="text-green-600 hover:underline">Restore</button>
                            <button onclick="deleteBackup('${addslashes(backup.name)}')" class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                `;
            });

            html += `</tbody></table>`;
            container.innerHTML = html;
        }

        function prepareFormPath() {
            if (!currentPathId) {
                alert("Please verify a backup path first!");
                return false;
            }
            document.getElementById('form_backup_path_id').value = currentPathId;
        }

        function restoreBackup(filename) {
            if (confirm('⚠️ WARNING: This will overwrite the current database. Continue?')) {
                window.location.href = '/backup/restore-file/' + encodeURIComponent(filename);
            }
        }

        function deleteBackup(filename) {
            if (confirm('Delete this backup file permanently?')) {
                window.location.href = '/backup/delete/' + encodeURIComponent(filename);
            }
        }

        function deletePath(id, name) {
            if (confirm(`Are you sure you want to delete the backup path "${name}"?`)) {
                fetch(`/backup/path/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(() => location.reload())
                .catch(() => alert('Error deleting path'));
            }
        }

        function setDefaultPath(id, name) {
            if (confirm(`Set "${name}" as the default backup path?`)) {
                fetch(`/backup/path/${id}/default`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to set default path.');
                    }
                })
                .catch(() => alert('Error setting default path'));
            }
        }

        function addslashes(str) {
            return str.replace(/'/g, "\\'");
        }

        // Schedule Form Conditional Fields
        document.addEventListener('DOMContentLoaded', function() {
            const frequencySelect = document.getElementById('frequency');
            const dayOfWeekGroup = document.getElementById('day_of_week_group');
            const dayOfMonthGroup = document.getElementById('day_of_month_group');

            function toggleFields() {
                const freq = frequencySelect.value;

                if (freq === 'weekly' || freq === 'biweekly') {
                    dayOfWeekGroup.style.display = 'block';
                    dayOfMonthGroup.style.display = 'none';
                } else if (freq === 'monthly' || freq === 'quarterly' || freq === 'yearly') {
                    dayOfWeekGroup.style.display = 'none';
                    dayOfMonthGroup.style.display = 'block';
                } else {
                    dayOfWeekGroup.style.display = 'none';
                    dayOfMonthGroup.style.display = 'none';
                }
            }

            frequencySelect.addEventListener('change', toggleFields);
            toggleFields(); // Initial call
        });

        window.onload = function() {
            document.getElementById('addPathModal').classList.add('hidden');

            const savedPathId = localStorage.getItem('selectedBackupPathId');
            if (savedPathId) {
                const select = document.getElementById('backup_path_id');
                select.value = savedPathId;
                setTimeout(verifySelectedPath, 700);
            }
        };
    </script>
</x-app-layout>

{{-- Activity Log View Modal --}}
<div x-data="{ open: false, currentLog: null }" id="activity-modal">

    <!-- Modal Backdrop -->
    <div x-show="open"
         class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
         style="display: none;">

        <div @click.outside="open = false"
             class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
                <h3 class="font-semibold text-lg text-gray-800">Activity Log Details</h3>
                <button @click="open = false"
                        class="text-3xl leading-none text-gray-400 hover:text-gray-600 transition">
                    ×
                </button>
            </div>

            <!-- Body -->
            <template x-if="currentLog">
                <div class="p-6 space-y-5 text-sm">

                    <div class="grid grid-cols-2 gap-y-4">
                        <div>
                            <span class="text-gray-500 block text-xs">User</span>
                            <span class="font-medium"
                                  x-text="currentLog.user?.name || 'System'"></span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-xs">Date & Time</span>
                            <span class="font-medium"
                                  x-text="currentLog.created_at || '—'"></span>
                        </div>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-xs mb-1">Action</span>
                        <span class="inline-block px-4 py-1 text-xs font-semibold rounded-full"
                              :class="{
                                'bg-green-100 text-green-700': currentLog.action === 'created',
                                'bg-blue-100 text-blue-700': currentLog.action === 'updated',
                                'bg-red-100 text-red-700': currentLog.action === 'deleted',
                                'bg-gray-100 text-gray-700': currentLog.action === 'viewed'
                              }"
                              x-text="currentLog.action ? currentLog.action.charAt(0).toUpperCase() + currentLog.action.slice(1) : ''">
                        </span>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-xs mb-1">Description</span>
                        <p class="text-gray-700 leading-relaxed"
                           x-text="currentLog.description || 'No description'"></p>
                    </div>

                    <div>
                        <span class="text-gray-500 block text-xs mb-1">IP Address</span>
                        <p class="font-mono text-gray-700"
                           x-text="currentLog.ip_address || '—'"></p>
                    </div>

                    <div x-if="currentLog.user_agent">
                        <span class="text-gray-500 block text-xs mb-1">User Agent</span>
                        <p class="text-xs text-gray-500 break-all"
                           x-text="currentLog.user_agent"></p>
                    </div>
                </div>
            </template>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
                <button @click="open = false"
                        class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 rounded-xl font-medium transition">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    window.viewLog = function(logData) {
        const modal = document.getElementById('activity-modal');
        if (modal && modal._x_dataStack && modal._x_dataStack[0]) {
            modal._x_dataStack[0].currentLog = logData;
            modal._x_dataStack[0].open = true;
        }
    }
</script>

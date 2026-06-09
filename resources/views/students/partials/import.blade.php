
{{-- Import Modal --}}
<dialog id="importModal" class="modal backdrop:bg-black/60" x-data="importModal()">
    <div class="modal-box w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden">

        <!-- Header -->
        <div class="px-6 py-4 border-b flex items-center justify-between bg-gray-50">
            <h3 class="font-semibold text-lg text-gray-800">Import Students</h3>
            <button @click="closeModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6h12v12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data"
              @submit="if (!file) return $event.preventDefault()">
            @csrf

            <div class="p-6">

                <!-- Drag & Drop Area -->
                <div
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop"
                    @click="triggerFileInput"
                    class="border-2 border-dashed rounded-2xl p-8 text-center cursor-pointer transition-all"
                    :class="isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'">

                    <div class="mx-auto w-12 h-12 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903 5 5 0 0110.025 1.475A4.5 4.5 0 0119 12.5a4.5 4.5 0 01-4.5 4.5H7z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3" />
                        </svg>
                    </div>

                    <p class="text-gray-600 font-medium">Drop your Excel or CSV file here</p>
                    <p class="text-sm text-gray-500 mt-1">or click to browse</p>
                    <p class="text-xs text-gray-400 mt-4">Supported: .xlsx, .xls, .csv</p>
                </div>

                <!-- Hidden File Input -->
                <input type="file" id="fileInput" name="file" accept=".xlsx,.xls,.csv"
                       class="hidden" @change="handleFileSelect">

                <!-- Selected File Preview -->
                <div x-show="file" class="mt-4 p-3 bg-gray-50 rounded-xl flex items-center gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate" x-text="file.name"></p>
                        <p class="text-xs text-gray-500" x-text="formatBytes(file.size)"></p>
                    </div>
                    <button type="button" @click="removeFile"
                            class="text-red-500 hover:text-red-700 p-1 rounded-lg hover:bg-red-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6h12v12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t flex justify-end gap-3">
                <button type="button" @click="closeModal()"
                        class="px-5 py-2.5 text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        :disabled="!file"
                        class="px-6 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors flex items-center gap-2">
                    <span>Import Students</span>
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
function importModal() {
    return {
        file: null,
        isDragging: false,

        triggerFileInput() {
            document.getElementById('fileInput').click();
        },

        handleFileSelect(e) {
            this.file = e.target.files[0];
        },

        handleDrop(e) {
            this.isDragging = false;
            this.file = e.dataTransfer.files[0];
        },

        removeFile() {
            this.file = null;
            document.getElementById('fileInput').value = '';
        },

        formatBytes(bytes) {
            if (!bytes) return '';
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
        },

        closeModal() {
            this.file = null;
            document.getElementById('importModal').close();
        }
    }
}
</script>

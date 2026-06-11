{{-- Payables Page --}}
<x-app-layout>
    <div x-data="{
        tab: 'payables',
        createModalOpen: false,
        editModalOpen: false,
        penaltyCreateOpen: false,
        penaltyEditOpen: false,

        init() {
            // Read tab from URL on page load
            const urlParams = new URLSearchParams(window.location.search);
            const urlTab = urlParams.get('tab');

            if (urlTab === 'penalties') {
                this.tab = 'penalties';
            }

            // Watch for tab changes and update URL
            this.$watch('tab', (newTab) => {
                const url = new URL(window.location);
                url.searchParams.set('tab', newTab);
                window.history.replaceState({}, '', url);
            });
        }
    }">

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Payables') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- Tabs -->
                <div class="bg-white shadow-sm rounded-lg mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px">
                            <button @click="tab = 'payables'"
                                    :class="{ 'border-blue-500 text-blue-600' : tab === 'payables', 'border-transparent text-gray-500 hover:text-gray-700' : tab !== 'payables' }"
                                    class="py-4 px-6 border-b-2 font-medium text-sm">
                                Payables
                            </button>
                            <button @click="tab = 'penalties'"
                                    :class="{ 'border-blue-500 text-blue-600' : tab === 'penalties', 'border-transparent text-gray-500 hover:text-gray-700' : tab !== 'penalties' }"
                                    class="py-4 px-6 border-b-2 font-medium text-sm">
                                Penalties
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Payables Tab -->
                <div x-show="tab === 'payables'">
                    @include('payables.partials.payables_content')
                </div>

                <!-- Penalties Tab -->
                <div x-show="tab === 'penalties'">
                    @include('payables.partials.penalties_content')
                </div>

            </div>
        </div>

        @include('payables.partials.create')
        @include('payables.partials.edit')
        @include('payables.partials.penalty_create')
        @include('payables.partials.penalty_edit')

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('penaltyModal', {
                penaltyEditOpen: false,
                editingPenalty: {},

                openEdit(data) {
                    this.editingPenalty = data || {};
                    this.penaltyEditOpen = true;
                },

                closeEdit() {
                    this.penaltyEditOpen = false;
                    // Clear data after modal closes
                    setTimeout(() => this.editingPenalty = {}, 300);
                }
            });
        });

        // Listen for edit button click
        document.addEventListener('open-penalty-edit', (e) => {
            const data = e.detail;
            Alpine.store('penaltyModal').openEdit(data);
        });
    </script>
</x-app-layout>

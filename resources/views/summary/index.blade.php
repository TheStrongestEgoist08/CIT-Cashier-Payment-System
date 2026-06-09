
{{-- Summary --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Summary') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Student filters S.Y, Grade, Section, Search --}}
            @include('summary.partials.student-filter')

            {{-- Statement of Account Print Preview Button and Summary of Account Print Preview Button PDF to be exact--}}
            @include('summary.partials.soa-panel')

            {{-- Table --}}
            @include('summary.partials.table')
        </div>
    </div>
</x-app-layout>

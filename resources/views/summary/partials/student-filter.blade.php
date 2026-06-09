<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
    <div class="p-4">
        <form action="{{ route('summary') }}" method="GET">
            <div class="flex flex-wrap items-end gap-2">

                {{-- Search --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Search
                    </label>
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Name, Student #, or LRN"
                        class="w-full border-gray-300 rounded-md shadow-sm">
                </div>

                {{-- School Year --}}
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        School Year
                    </label>
                    <select name="school_year"
                        class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">All School Years</option>
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy }}"
                                {{ request('school_year') == $sy ? 'selected' : '' }}>
                                {{ $sy }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Grade Level --}}
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Grade Level
                    </label>
                    <select name="grade_level"
                        class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">All Grade Levels</option>
                        <option value="Grade 11"
                            {{ request('grade_level') == 'Grade 11' ? 'selected' : '' }}>
                            Grade 11
                        </option>
                        <option value="Grade 12"
                            {{ request('grade_level') == 'Grade 12' ? 'selected' : '' }}>
                            Grade 12
                        </option>
                    </select>
                </div>

                {{-- Section --}}
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Section
                    </label>
                    <select name="section"
                        class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section }}"
                                {{ request('section') == $section ? 'selected' : '' }}>
                                {{ $section }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Filter
                    </button>

                    <a href="{{ route('summary') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>
